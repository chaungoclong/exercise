<?php

namespace App\Models;


use App\Models\Role;
use App\Traits\Authorization\HasRole;
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

    // Gender
    public const GENDER_FEMALE = 0;
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE_NAME = 'female';
    public const GENDER_MALE_NAME = 'male';

    // User status
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE_NAME = 'inactive';
    public const STATUS_ACTIVE_NAME = 'active';

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
            return '';
        }

        return ($this->gender === User::GENDER_MALE)
            ? __(User::GENDER_MALE_NAME)
            : __(User::GENDER_FEMALE_NAME);
    }

    /**
     * convert status to string
     * @return [type] [description]
     */
    public function getStatusTitleAttribute(): string
    {
        return ($this->status === User::STATUS_ACTIVE)
            ? __(User::STATUS_ACTIVE_NAME)
            : __(User::STATUS_INACTIVE_NAME);
    }

    /**
     * convert date to 'Y-m-d' before save
     * @param [type] $value [description]
     */
    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = Carbon::parse($value)->format('Y-m-d');
    }


    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return formatName($this->first_name . ' ' . $this->last_name);
    }
}
