<?php

namespace App\Models;


use Carbon\Carbon;
use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Authorization\HasRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
     * Get User's Role
     * @return [type] [description]
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get all of the Project's Member for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectMembers(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'user_id', 'id');
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
     * @param string $value [description]
     */
    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * convert date to 'd-m-Y' before get
     *
     * @param string $value
     */
    public function getBirthdayAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
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


    // Check User'Role
    public function isAdmin(): bool
    {
        return (bool) (optional($this->role)->isAdmin());
    }

    public function isManager(): bool
    {
        return (bool) (optional($this->role)->isManager());
    }

    public function isEmployee(): bool
    {
        return (bool) (optional($this->role)->isEmployee());
    }

    public function isAdminGroup(): bool
    {
        return (bool) (optional($this->role)->isAdminGroup());
    }

    public function isUserGroup()
    {
        return (bool) (optional($this->role)->isUserGroup());
    }

    /**
     * Get Users's Projects
     *
     * @return Collection
     */
    public function getProjectsAttribute(): Collection
    {
        return Project::whereIn(
            'id',
            $this->projectMembers->pluck('project_id')->toArray()
        )->get();
    }
}
