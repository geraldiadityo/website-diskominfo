<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Publication extends Model
{
    //
    protected $fillable = [
        'user_id',
        'tipe_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'published_at',
        'download_count'
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'published_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class);
    }

    public static function booted()
    {
        static::saving(function (Publication $publication) {
            if ($publication->isDirty('file_path') && $publication->file_path) {
                $extension = pathinfo($publication->file_path, PATHINFO_EXTENSION);
                $publication->file_type = $extension ?: 'unknown';

                $disk = Storage::disk('public');
                if ($disk->exists($publication->file_path)) {
                    $publication->file_size = $disk->size($publication->file_path);
                } else {
                    $publication->file_size = 0;
                }
            }
        });

        static::deleting(function (Publication $publication) {
            if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
                Storage::disk('public')->delete($publication->file_path);
            }
        });

        static::updating(function (Publication $publication) {
            if ($publication->isDirty('file_path')) {
                $oldFile = $publication->getOriginal('file_path');
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        });
    }
}
