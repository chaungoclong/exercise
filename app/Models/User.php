<?php

namespace App\Models;

use App\Constants\GenderType;
use App\Constants\UserStatus;
use App\Models\Role;
use App\Traits\Authorization\HasRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        HasRole,
        HasApiTokens,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'last_name',
        'first_name',
        'email',
        'password',
        'phone',
        'gender',
        'birthday',
        'address',
        'avatar',
        'status',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * [role description]
     * @return [type] [description]
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * [isActive description]
     * @return boolean [description]
     */
    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    /**
     * convert gender to string
     * @return [type] [description]
     */
    public function getGenderTitleAttribute(): string
    {
        if ($this->gender === null) {
            return __('other');
        }

        return ($this->gender === GenderType::MALE)
            ? __(GenderType::MALE_TITLE)
            : __(GenderType::FEMALE_TITLE);
    }

    /**
     * convert status to string
     * @return [type] [description]
     */
    public function getStatusTitleAttribute(): string
    {
        return ($this->status === UserStatus::ACTIVE)
            ? __(UserStatus::ACTIVE_TITLE)
            : __(UserStatus::INACTIVE_TITLE);
    }

    /**
     * convert date to 'Y-m-d' before save
     * @param [type] $value [description]
     */
    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function getFullNameAttribute()
    {
        return formatName($this->first_name . ' ' . $this->last_name);
    }
}
