<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Device extends Model
{
    use HasFactory,
        HasSchemalessAttributes,
        HasSeoOption,
        HasSlugFromTranslation,
        HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'languages',
        'slug',
        'ordering',
        'config',
        'brand_id',
    ];

    protected $casts = [
        'published'  => BooleanEnum::class,
        'languages'  => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'config'     => 'array',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

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
