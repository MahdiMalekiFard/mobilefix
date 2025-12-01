<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\YesNoEnum;
use App\Helpers\Constants;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property string $title
 * @property string $description
 */
class Team extends Model implements HasMedia
{
    use HasFactory, HasSchemalessAttributes, InteractsWithMedia;

    protected $fillable = [
        'name', 'job', 'special', 'config',
    ];

    protected $casts = [
        'config'  => 'array',
        'special' => YesNoEnum::class,
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                    ->fit(Fit::Crop, 100, 100)
                    ->format('webp')
                    ->quality(85);
                
                $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                    ->fit(Fit::Crop, 720, 720)
                    ->format('webp')
                    ->quality(85);
            });
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
    public function config(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }
}
