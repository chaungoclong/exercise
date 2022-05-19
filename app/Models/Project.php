<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    public const STATUS_ON_HOLD = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELLED = 3;

    protected $fillable = [
        'name',
        'slug',
        'detail',
        'duration',
        'revenue',
        'status',
        'start_date',
        'due_date'
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'due_date' => 'date:d-m-Y'
    ];

    /**
     * Get all of the Project's Member for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectMembers(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'id');
    }

    /**
     * Get all of the Report for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'project_id', 'id');
    }

    public function getCreatedAtTextAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d-m-Y');
    }

    public function getStartDateTextAttribute()
    {
        return Carbon::parse($this->attributes['start_date'])->format('d-m-Y');
    }

    public function getDeadlineTextAttribute()
    {
        return ($this->attributes['due_date'])
            ? Carbon::parse($this->attributes['due_date'])->format('d-m-Y')
            : 'N/A';
    }

    /**
     * Get project status text
     *
     * @return array
     */
    public function getStatusText(): array
    {
        return [
            self::STATUS_ON_HOLD => config(
                "constants.project_status." . self::STATUS_ON_HOLD . ".name"
            ),
            self::STATUS_CANCELLED => config(
                "constants.project_status." . self::STATUS_CANCELLED . ".name"
            ),
            self::STATUS_IN_PROGRESS => config(
                "constants.project_status." . self::STATUS_IN_PROGRESS . ".name"
            ),
            self::STATUS_COMPLETED => config(
                "constants.project_status." . self::STATUS_COMPLETED . ".name"
            )
        ];
    }

    /**
     * convert date to 'Y-m-d' before save
     * @param string $value [description]
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)
            ->format('Y-m-d');
    }

    /**
     * convert date to 'Y-m-d' before save
     * @param string $value [description]
     */
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::parse($value)
            ->format('Y-m-d');
    }

    /**
     * Get Project's Users
     *
     * @return Collection
     */
    public function getUsersAttribute(): Collection
    {
        return User::whereIn(
            'id',
            $this->projectMembers->pluck('user_id')->toArray()
        )->get();
    }
}
