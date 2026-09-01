<?php

namespace App\Services;

class ChatClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(config('services.chatbot_service_url'));
    }

    /**
     * @param  array<int, array{role: string, text: string}>  $history
     * @return array{status: int, body: mixed}
     */
    public function send(string $message, array $history): array
    {
        return $this->request('POST', '/api/chat', [
            'json' => ['message' => $message, 'history' => $history],
            // Gemini calls can genuinely take several seconds, more so on a
            // multi-round tool-calling turn — matches the gateway's own
            // proxy_read_timeout for this route (gateway/nginx.conf).
            'timeout' => 60,
        ]);
    }
}
