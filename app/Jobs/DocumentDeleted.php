<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DocumentDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public readonly string $name,
        public readonly string $mimeType,
        public readonly int    $size,
        public readonly string $source, // 'manual' | 'expired'
        public readonly string $deletedAt,
    ) {
        $this->onConnection('rabbitmq');
    }

    public function handle(): void
    {
        Log::channel('deletions')->info('Document deleted', [
            'name'       => $this->name,
            'mime_type'  => $this->mimeType,
            'size'       => $this->size,
            'source'     => $this->source,
            'deleted_at' => $this->deletedAt,
        ]);
    }
}
