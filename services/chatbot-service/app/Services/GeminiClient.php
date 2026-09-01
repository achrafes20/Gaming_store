<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Talks to the Gemini Generative Language API (Google AI Studio —
 * https://aistudio.google.com/apikey). Thin wrapper, no SDK: the same
 * "just POST it with the Http facade" style used for every other
 * inter-service call in this codebase, and for the OTLP spans this service
 * exports too (see App\Support\Tracing) — one fewer dependency to pin and
 * audit for a single endpoint.
 */
class GeminiClient
{
    /**
     * @param  array<int, array{role: string, parts: array}>  $contents
     * @param  array<int, array>  $toolDeclarations
     * @return array<int, array> the model's response "parts" — each either
     *                           ['text' => string] or
     *                           ['functionCall' => ['name' => string, 'args' => array]]
     */
    public function generateContent(string $systemPrompt, array $contents, array $toolDeclarations = []): array
    {
        if (! config('services.gemini.api_key')) {
            throw new RuntimeException('GEMINI_API_KEY is not configured — see docs/chatbot.md.');
        }

        $payload = [
            'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => $contents,
        ];

        if ($toolDeclarations) {
            $payload['tools'] = [['functionDeclarations' => $toolDeclarations]];
        }

        $model = config('services.gemini.model');
        $url = rtrim(config('services.gemini.api_url'), '/')."/models/{$model}:generateContent";

        // 2 retries, 500ms apart: found real, transient connection timeouts
        // and 503 "high demand" responses from the live API while testing
        // this against a real key — both are exactly the kind of blip a
        // third-party API call should absorb rather than surface as a
        // broken chat turn.
        $response = Http::withHeaders(['x-goog-api-key' => config('services.gemini.api_key')])
            ->timeout(30)
            ->retry(2, 500, throw: false)
            ->post($url, $payload);

        if ($response->failed()) {
            throw new RuntimeException("Gemini API error ({$response->status()}): {$response->body()}");
        }

        return $response->json('candidates.0.content.parts', []);
    }
}
