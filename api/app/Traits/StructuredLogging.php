<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait StructuredLogging
{
    protected function logInfo(string $message, array $context = []): void
    {
        Log::channel('structured')->info($message, $context);
    }
}
