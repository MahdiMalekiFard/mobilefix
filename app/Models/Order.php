<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Order extends Model implements HasMedia
{
    use HasFactory,
        HasSchemalessAttributes,
        InteractsWithMedia,
        SoftDeletes;

    protected $fillable = [
        'order_number',
        'tracking_code',
        'status',
        'total',
        'user_id',
        'address_id',
        'payment_method_id',
        'brand_id',
        'device_id',
        'user_note',
        'admin_note',
        'config',
    ];

    protected $casts = [
        'config'     => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm']);
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class)->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function successfulTransactions()
    {
        return $this->hasMany(Transaction::class)->successful();
    }

    public function pendingTransactions()
    {
        return $this->hasMany(Transaction::class)->pending();
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Model Attributes -------------------------------------------------------------------------- */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : $this->config()->get('name');
    }

    public function getUserPhoneAttribute()
    {
        return $this->user ? $this->user->mobile : $this->config()->get('phone');
    }

    public function getUserEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->config()->get('email');
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function config(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }
}
