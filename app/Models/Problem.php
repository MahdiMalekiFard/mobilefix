<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslationAuto;
use App\Traits\HasSchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;


class Problem extends Model
{
    use HasFactory;
    use HasTranslationAuto;
    use HasSchemalessAttributes;

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
