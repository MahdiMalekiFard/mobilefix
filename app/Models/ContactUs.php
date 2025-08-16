<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\YesNoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property boolean $is_read
 * @property boolean $is_replied
 */
class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contactuses';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'is_read',
        'is_replied',
    ];

    protected $casts = [
        'is_read' => YesNoEnum::class,
        'is_replied' => YesNoEnum::class,
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

}
