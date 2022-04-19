<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use App\Traits\Authorization\HasPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory,
        HasPermission;

    // slug of role default
    public const ADMIN_ROLE_SLUG = 'admin';
    public const MANAGER_ROLE_SLUG = 'manager';
    public const EMPLOYEE_ROLE_SLUG = 'employee';

    protected $fillable = [
        'name',
        'slug',
        'is_default'
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

    public function isAdmin(): bool
    {
        return ($this->slug === Role::ADMIN_ROLE_SLUG);
    }

    public function isManager(): bool
    {
        return ($this->slug === Role::MANAGER_ROLE_SLUG);
    }

    public function isEmployee(): bool
    {
        return ($this->slug === Role::EMPLOYEE_ROLE_SLUG);
    }

    public function isAminGroup(): bool
    {
        return ($this->isAdmin() || $this->isManager());
    }

    public function isUserGroup(): bool
    {
        return !$this->isAminGroup();
    }
}
