<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory,
        SoftDeletes;

    // Status constants
    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;

    // Working type constants
    public const WORKING_TYPE_OFFSITE = 0;
    public const WORKING_TYPE_ONSITE = 1;
    public const WORKING_TYPE_REMOTE = 2;
    public const WORKING_TYPE_OFF = 3;

    // Max working time per day
    public const MAX_TIME_PER_DAY = 8;

    protected $fillable = [
        'user_id',
        'position_id',
        'project_id',
        'working_type',
        'working_time',
        'date',
        'note',
        'status'
    ];

    protected $casts = [
        'date' => 'date:d-m-Y',
    ];

    /**
     * Get the user that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the project that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * Get the position that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    /**
     * Convert date format to 'Y-m-d' before save
     */
    // public function setDateAttribute($value)
    // {
    //     $this->attributes['date'] = Carbon::parse($value)
    //         ->format('Y-m-d');
    // }
}
