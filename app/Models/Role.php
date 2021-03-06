<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use App\Traits\Authorization\HasPermission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory,
        HasPermission,
        SoftDeletes;

    // Role Root
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';
    public const EMPLOYEE = 'employee';

    protected $fillable = [
        'name',
        'slug',
        'is_default',
        'is_user_define',
    ];

    /**
     * casting
     * @var [type]
     */
    protected $casts = [
        'is_default'     => 'boolean',
        'is_user_define' => 'boolean'
    ];

    /**
     * [permissions description]
     * @return [type] [description]
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',
            'permission_id'
        );
    }

    /**
     * [users description]
     * @return [type] [description]
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    /**
     * get role default
     * @return [type] [description]
     */
    public function getDefault(): ?Role
    {
        return $this->where('is_default', true)->first();
    }

    /**
     * check role is admin
     * @return boolean [description]
     */
    public function isAdmin(): bool
    {
        return ($this->slug === Role::ADMIN);
    }

    /**
     * check role is manager
     * @return boolean [description]
     */
    public function isManager(): bool
    {
        return ($this->slug === Role::MANAGER);
    }

    /**
     * check role is employee
     * @return boolean [description]
     */
    public function isEmployee(): bool
    {
        return ($this->slug === Role::EMPLOYEE);
    }

    // check role is admin or manager which is not defined by user
    public function isAdminGroup(): bool
    {
        return ($this->isAdmin() || $this->isManager());
    }

    /**
     * check role is employee or other role which is defined by user
     * @return boolean [description]
     */
    public function isUserGroup(): bool
    {
        return !$this->isAdminGroup();
    }

    /**
     * Get list Role for Index page
     *
     * @return Collection
     */
    public function getListIndex(): Collection
    {
        return $this->with('users')
            ->withCount('users')
            ->orderBy('created_at')
            ->get();
    }


    // /**
    //  * The "booted" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function booted()
    // {
    //     static::deleting(function ($role) {
    //         $role->permissions()->detach();
    //     });
    // }
}
