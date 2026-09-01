<?php

namespace App\Http\Controllers;

use App\Services\ChatClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Server-side proxy to chatbot-service (Phase 8) — the browser never sees
 * the JWT (same boundary SessionJwtGuard already keeps for everything
 * else), it only ever talks to this route. Conversation history lives in
 * the PHP session, not the client, for the same reason.
 */
class ChatController extends Controller
{
    public function store(Request $request, ChatClient $chat)
    {
        $data = $request->validate(['message' => 'required|string|max:2000']);

        $history = Session::get('chat_history', []);
        $result = $chat->send($data['message'], $history);

        if ($result['status'] !== 200) {
            return response()->json(['reply' => "Sorry, I'm having trouble right now — please try again in a moment."], 200);
        }

        $body = $result['body'];
        // ApiObject::wrap() turns the JSON list of {role,text} entries into a
        // Collection<ApiObject> (see App\Support\ApiObject) — ->toArray()
        // unwraps it back to plain nested arrays, both for session storage
        // and so it round-trips as valid JSON on the next ChatClient::send().
        $newHistory = isset($body->history) ? $body->history->toArray() : $history;
        Session::put('chat_history', $newHistory);

        return response()->json(['reply' => (string) ($body->reply ?? '...')]);
    }

    public function clear()
    {
        Session::forget('chat_history');

        return response()->noContent();
    }
}
