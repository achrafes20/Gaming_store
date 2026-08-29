<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

/**
 * Publishes domain events to RabbitMQ (exchange "gaming_store_events").
 * notifications-service consumes them to send emails asynchronously —
 * orders-service never calls Mail::send() itself (see docs/architecture.md).
 */
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
            // Le workflow métier (commande créée) ne doit pas échouer si RabbitMQ est down —
            // on logue pour investigation mais on ne bloque pas le checkout.
            Log::error("Failed to publish event {$eventName}", ['error' => $e->getMessage()]);
        }
    }
}
