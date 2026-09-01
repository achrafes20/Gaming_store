<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatTools;
use App\Services\GeminiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ChatController extends Controller
{
    /** Bounds the tool-call loop — a model that keeps calling tools forever
     *  should get a clear "couldn't finish" answer, not hang the request. */
    private const MAX_TOOL_ROUNDS = 5;

    public function __construct(private GeminiClient $gemini, private ChatTools $tools) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,model',
            'history.*.text' => 'required_with:history|string',
        ]);

        $user = $request->attributes->get('auth_user');
        $callerJwt = substr($request->header('Authorization', ''), 7);

        $contents = collect($data['history'] ?? [])
            ->map(fn (array $m) => ['role' => $m['role'], 'parts' => [['text' => $m['text']]]])
            ->values()->all();
        $contents[] = ['role' => 'user', 'parts' => [['text' => $data['message']]]];

        $declarations = $this->tools->declarationsFor($user['role'] ?? 'client');
        $reply = $this->converse($this->systemPrompt($user), $contents, $declarations, $callerJwt);

        $history = array_merge($data['history'] ?? [], [
            ['role' => 'user', 'text' => $data['message']],
            ['role' => 'model', 'text' => $reply],
        ]);

        return response()->json(['reply' => $reply, 'history' => $history]);
    }

    private function converse(string $systemPrompt, array $contents, array $declarations, string $callerJwt): string
    {
        for ($round = 0; $round < self::MAX_TOOL_ROUNDS; $round++) {
            $parts = $this->gemini->generateContent($systemPrompt, $contents, $declarations);
            $partsCollection = Collection::make($parts);

            $callPart = $partsCollection->first(fn (array $part) => isset($part['functionCall']));
            if (! $callPart) {
                return $partsCollection->pluck('text')->filter()->implode('');
            }

            $call = $callPart['functionCall'];
            $result = $this->tools->execute($call['name'], $call['args'] ?? [], $callerJwt);

            // Same Struct-vs-list issue as ChatTools' functionResponse
            // wrapping: Gemini sent us args:{} for a no-argument tool, PHP's
            // json_decode turns an empty JSON *object* into an empty PHP
            // *array* (there's no distinct empty-map type), and echoing
            // that array back gets serialized as [] — a list — which
            // Gemini's own proto validation then rejects when it's handed
            // straight back as part of the conversation history.
            if (empty($callPart['functionCall']['args'])) {
                $callPart['functionCall']['args'] = new \stdClass;
            }

            $contents[] = ['role' => 'model', 'parts' => [$callPart]];
            $contents[] = ['role' => 'user', 'parts' => [[
                'functionResponse' => ['name' => $call['name'], 'response' => $result],
            ]]];
        }

        return "Désolé, je n'ai pas pu terminer cette demande — pouvez-vous reformuler ?";
    }

    private function systemPrompt(array $user): string
    {
        $role = $user['role'] ?? 'client';
        $roleNote = $role === 'admin'
            ? 'As an admin, you may also answer store-wide questions (all orders, revenue, stock levels) using the admin tools available to you.'
            : "You can only see this user's own data — never claim to know about other customers or store-wide statistics, and never invent numbers if a tool isn't available to get them.";

        return <<<PROMPT
        You are the assistant for NextLevelGaming, an online gaming-gear store built as a microservices demo
        (catalog, orders, users, notifications, and this chatbot service — see the project's own
        docs/architecture.md if asked how it's built).

        The current user is "{$user['name']}" ({$user['email']}), role: {$role}.

        Answer questions about the store, its products, and — using the tools available to you — the user's
        own cart and order history. {$roleNote}

        Keep answers concise and friendly, in the same language the user writes in. Never invent data — always
        call a tool to look up real information rather than guessing. If a tool returns an error or says the
        user isn't authorized, say so plainly instead of making something up.
        PROMPT;
    }
}
