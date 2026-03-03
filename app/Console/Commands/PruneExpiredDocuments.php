<?php

namespace App\Console\Commands;

use App\Jobs\DocumentDeleted;
use App\Models\Document;
use Illuminate\Console\Command;

class PruneExpiredDocuments extends Command
{
    protected $signature = 'documents:prune';
    protected $description = 'Delete expired documents and their stored files';

    public function handle(): int
    {
        $expired = Document::where('expires_at', '<=', now())->get();

        if ($expired->isEmpty()) {
            $this->info('No expired documents found.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($expired as $document) {
            DocumentDeleted::dispatch(
                $document->original_name,
                $document->mime_type,
                $document->size,
                'expired',
                now()->toISOString(),
            );

            $document->deleteFile();
            $document->delete();
            $count++;
        }

        $this->info("Pruned {$count} expired document(s).");

        return self::SUCCESS;
    }
}
