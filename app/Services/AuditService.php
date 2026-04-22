<?php

namespace App\Services;

use App\Models\AuditEvent;
use Illuminate\Http\Request;

class AuditService
{
    public function log(string $eventType, ?string $subjectType = null, ?int $subjectId = null, array $context = []): void
    {
        /** @var Request|null $request */
        $request = request();

        AuditEvent::create([
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'ip_address' => $request?->ip(),
            'user_agent' => substr((string) $request?->userAgent(), 0, 255),
            'context' => $context,
        ]);
    }
}
