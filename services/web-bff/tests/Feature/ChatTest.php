<?php

namespace Tests\Feature;

use App\Services\ChatClient;
use App\Support\ApiObject;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ChatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // /chat is throttled per-IP (SECURITY.md pattern) — see AuthTest for
        // why this Cache::flush() is needed between tests.
        Cache::flush();
    }

    public function test_a_guest_cannot_reach_the_chat_endpoint(): void
    {
        $this->post('/chat', ['message' => 'hello'])->assertRedirect('/login');
    }

    public function test_an_authenticated_user_gets_a_reply_and_the_session_history_is_updated(): void
    {
        Session::put('jwt', 'a.jwt.token');
        Session::put('user', (object) ['id' => 1, 'name' => 'Alice', 'role' => 'client']);

        $this->mock(ChatClient::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->with('hi there', [])
                ->andReturn(['status' => 200, 'body' => ApiObject::wrap([
                    'reply' => 'Hello! How can I help?',
                    'history' => [
                        ['role' => 'user', 'text' => 'hi there'],
                        ['role' => 'model', 'text' => 'Hello! How can I help?'],
                    ],
                ])]);
        });

        $this->postJson('/chat', ['message' => 'hi there'])
            ->assertOk()
            ->assertJsonPath('reply', 'Hello! How can I help?');

        $this->assertCount(2, Session::get('chat_history'));
        $this->assertEquals('hi there', Session::get('chat_history')[0]['text']);
    }

    public function test_a_downstream_failure_degrades_to_a_friendly_message_not_a_500(): void
    {
        Session::put('jwt', 'a.jwt.token');
        Session::put('user', (object) ['id' => 1, 'name' => 'Alice', 'role' => 'client']);

        $this->mock(ChatClient::class, function ($mock) {
            $mock->shouldReceive('send')->once()->andReturn(['status' => 503, 'body' => null]);
        });

        $this->postJson('/chat', ['message' => 'hi'])
            ->assertOk()
            ->assertJsonStructure(['reply']);
    }
}
