<?php

namespace App\Services;

use App\Support\ApiObject;
use App\Support\Tracing;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Session;

/**
 * Thin JSON-over-HTTP client shared by the three backend service clients.
 * web-bff never talks to a database directly for domain data — every
 * request goes through one of the microservices (see docs/architecture.md).
 */
abstract class ApiClient
{
    protected Client $http;

    public function __construct(string $baseUri)
    {
        $this->http = new Client(['base_uri' => $baseUri, 'timeout' => 10, 'http_errors' => false]);
    }

    protected function authHeaders(): array
    {
        $token = Session::get('jwt');

        return $token ? ['Authorization' => "Bearer {$token}"] : [];
    }

    /** @return array{status:int, body:mixed} */
    protected function request(string $method, string $uri, array $options = []): array
    {
        // Propagate the W3C traceparent (Phase 6) so every downstream call
        // made while handling this request is part of the same distributed
        // trace — see App\Support\Tracing / docs/observability.md.
        $tracingHeaders = ($header = Tracing::outgoingHeader()) ? ['traceparent' => $header] : [];
        $options['headers'] = array_merge($this->authHeaders(), $tracingHeaders, $options['headers'] ?? []);

        try {
            $response = $this->http->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            return ['status' => 503, 'body' => ApiObject::wrap(['message' => 'Service unavailable, please try again.'])];
        }

        $decoded = json_decode($response->getBody()->getContents(), true);

        return ['status' => $response->getStatusCode(), 'body' => ApiObject::wrap($decoded ?? [])];
    }
}
