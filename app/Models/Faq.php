<?php

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasCategory;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

/**
 * @property string $title
 * @property string $description
 */
class Faq extends Model
{
    use CLogsActivity;
    use HasFactory;
    use HasTranslationAuto;
    use HasCategory;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'published_at',
        'category_id',
        'favorite',
        'ordering',
        'languages',
    ];

    protected $casts = [
        'published'    => BooleanEnum::class,
        'published_at' => 'date',
        'favorite'     => YesNoEnum::class,
        'languages'    => 'array',
        'created_at'   => 'date',
        'updated_at'   => 'date',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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
