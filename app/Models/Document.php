<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'size'       => 'integer',
    ];

    public function deleteFile(): void
    {
        Storage::disk($this->disk)->delete($this->path);
    }
}
