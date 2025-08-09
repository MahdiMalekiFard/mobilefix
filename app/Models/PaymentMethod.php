<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class PaymentMethod extends Model
{
    use HasFactory,
        HasSchemalessAttributes,
        HasTranslationAuto,
        SoftDeletes;

    protected $fillable = [
        'languages',
        'provider',
        'published',
        'is_default',
        'config',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'is_default' => BooleanEnum::class,
        'languages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
