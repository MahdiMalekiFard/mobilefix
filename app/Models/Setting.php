<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SettingEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed> $permissions
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property bool|null $show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withExtraAttributes()
 * @mixin \Eloquent
 */
class Setting extends Model implements HasMedia
{
    use CLogsActivity, HasSchemalessAttributes, InteractsWithMedia;

    protected $fillable = [
        'key',
        'permissions',
        'extra_attributes', // nullable
        'show',
    ];

    protected $casts = [
        'permissions' => 'array',
        'show'        => 'boolean',
    ];

    /**
     * | Model Configuration ----------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)
                    ->fit(Fit::Crop, 100, 100);

                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)
                    ->fit(Fit::Crop, 512, 512);

                $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                    ->fit(Fit::Crop, 720, 720);
            });
    }

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Scope ------------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function getTitleAttribute(): string
    {
        return SettingEnum::tryFrom($this->key)->title();
    }
    /**
     * | Model Custom Methods ---------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
}
