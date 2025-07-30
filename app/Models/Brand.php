<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasSeoOption;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;

/**
 * @property string $title
 * @property string $description
 */
class Brand extends Model implements HasMedia
{
    use HasFactory,
        HasTranslationAuto,
        HasSlugFromTranslation,
        HasSeoOption,
        HasSchemalessAttributes,
        SoftDeletes,
        InteractsWithMedia;

    protected $fillable = [
        'published',
        'languages',
        'slug',
        'ordering',
        'config',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'config' => 'array',
    ];

    public array $translatable = [
        'title','description'
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


    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */

    public function config()
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }

}
