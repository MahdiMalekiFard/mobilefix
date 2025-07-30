<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,
        HasSchemalessAttributes,
        SoftDeletes;

    protected $fillable = [
        'order_number',
        'status',
        'total',
        'user_id',
        'address_id',
        'payment_method_id',
        'brand_id',
        'device_id',
        'user_note',
        'admin_note',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
