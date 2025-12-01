<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property string $title
 * @property string $description
 */
class Brand extends Model implements HasMedia
{
    use HasFactory,
        HasSchemalessAttributes,
        HasSeoOption,
        HasSlugFromTranslation,
        HasTranslationAuto,
        InteractsWithMedia,
        SoftDeletes;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'languages',
        'slug',
        'ordering',
        'config',
    ];

    protected $casts = [
        'published'  => BooleanEnum::class,
        'languages'  => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'config'     => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    // Small logo for website display
                    $this->addMediaConversion('logo-small')
                        ->width(300)
                        ->height(150)
                        ->fit(Fit::Contain, 300, 150)
                        ->format('webp')
                        ->quality(90);
                    
                    // Square version for admin panel
                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->fit(Fit::Crop, 720, 720)
                        ->format('webp')
                        ->quality(85);
                }
            );
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function devices()
    {
        return $this->hasMany(Device::class, 'brand_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'brand_id');
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function config()
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }
}
