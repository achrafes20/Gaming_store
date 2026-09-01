<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmedMail;
use App\Mail\WelcomeMail;
use App\Support\Metrics;
use App\Support\Tracing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Long-running worker: consumes domain events published by orders-service
 * and users-service, and turns them into emails. This is the only service
 * that talks to RabbitMQ as a consumer (see docs/architecture.md).
 */
class ConsumeEvents extends Command
{
    protected $signature = 'events:consume';

    protected $description = 'Consume gaming_store_events from RabbitMQ and send the corresponding emails';

    public function handle(): int
    {
        $connection = new AMQPStreamConnection(
            config('services.rabbitmq.host'),
            config('services.rabbitmq.port'),
            config('services.rabbitmq.user'),
            config('services.rabbitmq.password'),
            // A consumer blocks waiting for messages, unlike the fail-fast publisher —
            // needs a long read timeout plus a heartbeat to keep the connection alive.
            connection_timeout: 5,
            read_write_timeout: 3600,
            keepalive: true,
            heartbeat: 30,
        );
        $channel = $connection->channel();

        $channel->exchange_declare('gaming_store_events', 'topic', false, true, false);
        $channel->queue_declare('notifications_queue', false, true, false, false);
        $channel->queue_bind('notifications_queue', 'gaming_store_events', 'order.created');
        $channel->queue_bind('notifications_queue', 'gaming_store_events', 'user.registered');

        $this->info('Listening for events on notifications_queue...');

        $channel->basic_consume('notifications_queue', '', false, false, false, false, function (AMQPMessage $message) {
            $this->handleMessage($message);
        });

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();

        return self::SUCCESS;
    }

    private function handleMessage(AMQPMessage $message): void
    {
        $body = json_decode($message->getBody(), true);
        $event = $body['event'] ?? null;
        $payload = $body['payload'] ?? [];
        $traceparent = $body['traceparent'] ?? null;

        Tracing::span("process {$event}", $traceparent, function () use ($event, $payload, $message) {
            $start = microtime(true);
            $registry = Metrics::registry();
            $status = 'success';

            try {
                match ($event) {
                    'order.created' => Mail::to($payload['email'])->send(new OrderConfirmedMail($payload)),
                    'user.registered' => Mail::to($payload['email'])->send(new WelcomeMail($payload)),
                    default => Log::warning("Unknown event received: {$event}"),
                };

                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                $this->info("Processed event: {$event}");
            } catch (\Throwable $e) {
                $status = 'error';
                Log::error("Failed to process event {$event}", ['error' => $e->getMessage()]);
                // Requeue once (assume transient failure e.g. SMTP hiccup); a real deployment
                // would route repeated failures to a dead-letter queue instead of looping forever.
                $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag'], false, false);
            } finally {
                // Golden Signals for this worker: traffic + errors (counter),
                // latency (histogram) — same shape as PrometheusMetrics on
                // the HTTP services, pushed instead of scraped (see Metrics).
                $registry->getOrRegisterCounter('app', 'events_processed_total', 'Total events consumed (traffic)', ['event', 'status'])
                    ->inc([(string) $event, $status]);
                $registry->getOrRegisterHistogram('app', 'event_processing_duration_seconds', 'Event processing duration in seconds (latency)', ['event'], [0.01, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5])
                    ->observe(microtime(true) - $start, [(string) $event]);
                Metrics::push();
            }
        });
    }
}
