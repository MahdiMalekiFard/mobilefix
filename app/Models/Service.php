<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $title
 * @property string $description
 * @property string $body
 */
class Service extends Model implements HasMedia
{
    use HasFactory;
    use HasSeoOption;
    use HasSlugFromTranslation;
    use HasTranslationAuto;
    use InteractsWithMedia;

    public array $translatable = [
        'title', 'description', 'body',
    ];

    protected $fillable = [
        'slug',
        'published',
        'languages',
        'icon',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                        ->fit(Fit::Crop, 100, 100)
                        ->format('webp')
                        ->quality(85);
                    
                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->fit(Fit::Crop, 720, 720)
                        ->format('webp')
                        ->quality(85);
                    
                    $this->addMediaConversion(Constants::RESOLUTION_854_480)
                        ->fit(Fit::Crop, 854, 480)
                        ->format('webp')
                        ->quality(85);
                }
            );
    }

    /**
     * Model Relations --------------------------------------------------------------------------
     */

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Model Attributes -------------------------------------------------------------------------- */
    public function getIconUrlAttribute(): ?string
    {
        if ( ! $this->icon) {
            return null;
        }

        return asset('assets/images/icon/' . $this->icon);
    }

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
