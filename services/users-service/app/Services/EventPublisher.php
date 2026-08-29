<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EventPublisher
{
    public function publish(string $eventName, array $payload): void
    {
        try {
            $connection = new AMQPStreamConnection(
                config('services.rabbitmq.host'),
                config('services.rabbitmq.port'),
                config('services.rabbitmq.user'),
                config('services.rabbitmq.password'),
                connection_timeout: 2,
                read_write_timeout: 2,
            );
            $channel = $connection->channel();
            $channel->exchange_declare('gaming_store_events', 'topic', false, true, false);

            $message = new AMQPMessage(json_encode([
                'event' => $eventName,
                'payload' => $payload,
                'emitted_at' => now()->toISOString(),
            ]), ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

            $channel->basic_publish($message, 'gaming_store_events', $eventName);

            $channel->close();
            $connection->close();
        } catch (\Throwable $e) {
            // Le workflow métier (inscription) ne doit pas échouer si RabbitMQ est down/indisponible.
            Log::error("Failed to publish event {$eventName}", ['error' => $e->getMessage()]);
        }
    }
}
