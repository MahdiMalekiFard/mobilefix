<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasSchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Device extends Model
{
    use HasFactory,
        HasTranslationAuto,
        HasSlugFromTranslation,
        HasSchemalessAttributes;

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

    public function config()
    {
        return SchemalessAttributes::createForModel($this, 'config');
    }

}
