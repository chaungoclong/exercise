<?php

namespace App\Traits\Authorization;

use App\Models\Role;
use Illuminate\Support\Collection;

trait HasRole
{
    /**
     * [assignRole description]
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function assignRole($key): bool
    {
        $role = $this->getRoleByKey($key);

        if ($role === null) {
            return false;
        }

        $this->role_id = $role->id;

        return $this->save();
    }

    /**
     * [removeRole description]
     * @return [type] [description]
     */
    public function removeRole(): bool
    {
        $this->role_id = null;

        return $this->save();
    }

    /**
     * [hasRole description]
     * @param  [type]  $key [description]
     * @return boolean      [description]
     */
    public function hasRole($key): bool
    {
        $role = $this->getRoleByKey($key);

        if ($role === null || $this->role === null) {
            return false;
        }

        return $this->role->id === $role->id;
    }

    /**
     * [getRoleByKey description]
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getRoleByKey($key):  ?Role
    {
        if (is_int($key)) {
            return Role::find($key);
        }

        if (is_string($key)) {
            return Role::where('slug', $key)->first();
        }

        if (!$key instanceof Role) {
            return null;
        }

        return Role::where('id', $key->id)->first();
    }

    /**
     * [hasPermission description]
     * @param  [type]  $key [description]
     * @return boolean      [description]
     */
    public function hasPermission($key): bool
    {
        return (bool) optional($this->role)->hasPermission($key);
    }

    /**
     * [hasAllPermission description]
     * @param  [type]  $keys [description]
     * @return boolean       [description]
     */
    public function hasAllPermission(...$keys): bool
    {
        return (bool) optional($this->role)->hasAllPermission($keys);
    }

    /**
     * [hasAnyPermission description]
     * @param  [type]  $keys [description]
     * @return boolean       [description]
     */
    public function hasAnyPermission(...$keys): bool
    {
        return (bool) optional($this->role)->hasAnyPermission($keys);
    }

    /**
     * [permissions description]
     * @return [type] [description]
     */
    public function getAllPermission(): ?Collection
    {
        return optional($this->role)->permissions;
    }
}
