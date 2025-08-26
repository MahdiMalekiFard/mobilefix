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

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function config()
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }
}
