<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'body',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                // Images
                'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp', 'image/svg+xml',
                // Documents / text
                'application/pdf', 'text/plain', 'text/csv', 'application/json', 'application/xml', 'text/xml',
                // Archives
                'application/zip',
                'application/x-zip-compressed',
                'application/x-rar',
                'application/x-rar-compressed',
                'application/vnd.rar',
                'application/x-7z-compressed',
                // Office
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                // Audio
                'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg',
                // Video
                'video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm',
            ])
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Only run for images
        if ($media && str_starts_with($media->mime_type, 'image/')) {
            $this->addMediaConversion('thumb')
                ->fit(Fit::Crop, 240, 240)
                ->queued();

            $this->addMediaConversion('preview')
                ->fit(Fit::Contain, 1024, 1024)
                ->queued();
        }
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
