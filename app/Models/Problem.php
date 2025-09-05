<?php

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Problem extends Model
{
    use HasFactory;
    use HasSchemalessAttributes;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'languages',
        'ordering',
        'min_price',
        'max_price',
        'config',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'config'    => 'array',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Model Attributes -------------------------------------------------------------------------- */
    public function getPriceRangeAttribute()
    {
        $minPrice = (float) $this->min_price;
        $maxPrice = (float) $this->max_price;

        if ($minPrice && $maxPrice) {
            if ($minPrice == $maxPrice) {
                return number_format($minPrice, 0) . ' ' . __('general.currency');
            }

            return number_format($minPrice, 0) . ' - ' . number_format($maxPrice, 0) . ' ' . __('general.currency');
        } elseif ($minPrice) {
            return number_format($minPrice, 0) . ' ' . __('general.currency') . ' +';
        } elseif ($maxPrice) {
            return __('general.price_up_to') . ' ' . number_format($maxPrice, 0) . ' ' . __('general.currency');
        }

        return __('general.not_set');
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function config()
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }
}
