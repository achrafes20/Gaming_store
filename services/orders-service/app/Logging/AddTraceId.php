<?php

namespace App\Logging;

use App\Support\Tracing;
use Monolog\LogRecord;

/**
 * Injects the current request's trace ID (Phase 6) into every log line's
 * `extra` field, so `grep`-ing (or a Loki query) for one trace ID surfaces
 * every log line across every service that touched that request — see
 * config/logging.php ('stderr' channel) and docs/observability.md.
 */
class AddTraceId
{
    public function __invoke(LogRecord $record): LogRecord
    {
        if ($traceId = Tracing::traceId()) {
            $record->extra['trace_id'] = $traceId;
        }

        return $record;
    }
}
