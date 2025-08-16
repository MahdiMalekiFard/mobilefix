<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasSeoOption;
use Spatie\Image\Enums\Fit;

/**
 * @property string $title
 * @property string $description
 * @property string $body
 */
class Service extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslationAuto;
    use HasSlugFromTranslation;
    use InteractsWithMedia;
    use HasSeoOption;

    protected $fillable = [
        'slug',
        'published',
        'languages',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array'
    ];

    public array $translatable = [
        'title','description', 'body'
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
             ->singleFile()
             ->useFallbackUrl('/assets/images/default/user-avatar.png')
             ->registerMediaConversions(
                 function () {
                     $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                          ->fit(Fit::Crop, 720, 720);
                 }
             );
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


    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */

}
