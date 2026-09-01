<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use ActsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();

        // /chat is throttled per-user (see AppServiceProvider) — the array
        // cache store persists across test methods in the same process, so
        // without this a later test could get 429'd by an earlier one
        // hitting the same limiter key.
        Cache::flush();
    }

    private function fakeGeminiText(string $text): array
    {
        return ['candidates' => [['content' => ['role' => 'model', 'parts' => [['text' => $text]]]]]];
    }

    private function fakeGeminiFunctionCall(string $name, array $args = []): array
    {
        return ['candidates' => [['content' => ['role' => 'model', 'parts' => [
            ['functionCall' => ['name' => $name, 'args' => $args]],
        ]]]]];
    }

    public function test_chat_requires_authentication(): void
    {
        $this->postJson('/api/chat', ['message' => 'hello'])->assertStatus(401);
    }

    public function test_a_client_never_receives_the_admin_tool_declaration(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response($this->fakeGeminiText('Hi there!')),
        ]);

        $this->withHeaders($this->jwtHeaders(role: 'client'))
            ->postJson('/api/chat', ['message' => 'hi'])
            ->assertOk();

        Http::assertSent(function ($request) {
            $names = collect($request['tools'][0]['functionDeclarations'] ?? [])->pluck('name');

            return ! $names->contains('get_all_orders') && $names->contains('get_my_cart');
        });
    }

    public function test_an_admin_does_receive_the_admin_tool_declaration(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response($this->fakeGeminiText('Sure.')),
        ]);

        $this->withHeaders($this->adminHeaders())
            ->postJson('/api/chat', ['message' => 'how many orders today?'])
            ->assertOk();

        Http::assertSent(function ($request) {
            $names = collect($request['tools'][0]['functionDeclarations'] ?? [])->pluck('name');

            return $names->contains('get_all_orders');
        });
    }

    public function test_a_tool_call_round_trip_reaches_the_real_endpoint_with_the_callers_jwt(): void
    {
        $cart = [['id' => 1, 'product_id' => 5, 'quantity' => 2, 'product' => ['name' => 'Razer DeathAdder']]];

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::sequence()
                ->push($this->fakeGeminiFunctionCall('get_my_cart'))
                ->push($this->fakeGeminiText('You have 2 Razer DeathAdder mice in your cart.')),
            'orders-service/api/cart' => Http::response($cart, 200),
        ]);

        $headers = $this->jwtHeaders(userId: 7, role: 'client');

        $response = $this->withHeaders($headers)
            ->postJson('/api/chat', ['message' => "what's in my cart?"])
            ->assertOk();

        $response->assertJsonPath('reply', 'You have 2 Razer DeathAdder mice in your cart.');

        // The tool call forwarded the caller's own JWT — not a service secret.
        Http::assertSent(fn ($request) => str_contains($request->url(), 'orders-service/api/cart')
            && $request->hasHeader('Authorization', $headers['Authorization']));

        // The second Gemini call carried the tool's result back as a functionResponse.
        Http::assertSent(function ($request) {
            $lastPart = collect($request['contents'] ?? [])->last()['parts'][0] ?? null;

            return isset($lastPart['functionResponse']) && $lastPart['functionResponse']['name'] === 'get_my_cart';
        });
    }

    public function test_admin_only_tool_is_rejected_server_side_even_if_a_client_role_somehow_triggers_it(): void
    {
        // Defense in depth (SECURITY.md): even if a client-role caller's model
        // attempted the admin tool, ChatTools applies no role check of its own —
        // it forwards the real JWT, and orders-service's own jwt.auth:admin
        // guard is what actually rejects it.
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::sequence()
                ->push($this->fakeGeminiFunctionCall('get_all_orders'))
                ->push($this->fakeGeminiText("You don't have access to that.")),
            'orders-service/api/admin/orders' => Http::response(['message' => 'Forbidden.'], 403),
        ]);

        $this->withHeaders($this->jwtHeaders(role: 'client'))
            ->postJson('/api/chat', ['message' => 'show me every order in the store'])
            ->assertOk()
            ->assertJsonPath('reply', "You don't have access to that.");
    }

    public function test_chat_is_rate_limited(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response($this->fakeGeminiText('ok')),
        ]);

        $headers = $this->jwtHeaders();

        for ($i = 0; $i < 20; $i++) {
            $this->withHeaders($headers)->postJson('/api/chat', ['message' => "msg {$i}"])->assertOk();
        }

        $this->withHeaders($headers)->postJson('/api/chat', ['message' => 'one too many'])->assertStatus(429);
    }
}
