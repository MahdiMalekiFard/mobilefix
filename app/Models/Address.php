<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property string $title
 * @property string $description
 */
class Address extends Model
{
    use HasFactory,
        HasSchemalessAttributes,
        SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'address',
        'is_default',
    ];

    protected $casts = [
        'is_default' => BooleanEnum::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'config' => 'array',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */


    /**
     * Model Relations --------------------------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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
