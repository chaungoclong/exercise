<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Get all of the Project's Member for the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectMembers(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'position_id', 'id');
    }

    /**
     * Get all of the Report for the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'position_id', 'id');
    }
}
