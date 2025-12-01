<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PageTypeEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributes as SchemalessAttributesAlias;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

/**
 * @property string $title
 * @property string $description
 */
class Page extends Model implements HasMedia
{
    use CLogsActivity,
        HasFactory,
        HasSeoOption,
        HasSlugFromTranslation,
        HasTranslationAuto,
        InteractsWithMedia,
        SchemalessAttributesTrait;

    public array $translatable = [
        'title', 'body',
    ];

    protected $fillable = [
        'languages', 'type', 'extra_attributes', 'view_count', 'slug',
    ];

    protected $casts = [
        'type'             => PageTypeEnum::class,
        'languages'        => 'array',
        'extra_attributes' => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                        ->fit(Fit::Crop, 100, 100)
                        ->format('webp')
                        ->quality(85);
                    
                    $this->addMediaConversion(Constants::RESOLUTION_854_480)
                        ->fit(Fit::Crop, 854, 480)
                        ->format('webp')
                        ->quality(85);
                    
                    $this->addMediaConversion(Constants::RESOLUTION_1280_720)
                        ->fit(Fit::Crop, 1280, 720)
                        ->format('webp')
                        ->quality(85);
                }
            );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Model Relations --------------------------------------------------------------------------
     */

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function extra(): SchemalessAttributesAlias
    {
        return SchemalessAttributesAlias::createForModel($this, 'extra_attributes');
    }
}
