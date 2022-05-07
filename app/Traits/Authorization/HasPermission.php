<?php

namespace App\Traits\Authorization;

use App\Models\Permission;
use Illuminate\Support\Collection;

trait HasPermission
{
    /**
     * [attachPermission description]
     * @param  [type] $keys [description]
     * @return [type]       [description]
     */
    public function attachPermission(...$keys): bool
    {
        try {
            $listPermissions = $this->getPermissionsExistInKeys($keys);

            $this->permissions()->sync($listPermissions->pluck('id'), false);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * [syncPermission description]
     * @param  [type] $keys [description]
     * @return [type]       [description]
     */
    public function syncPermission(...$keys): bool
    {
        try {
            $listPermissions = $this->getPermissionsExistInKeys($keys);

            $this->permissions()->sync($listPermissions->pluck('id'));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * [getPermissionByKey description]
     * @param  [Collection|Model|string|integer] $key
     * @return [Permission|null]
     */
    public function getPermissionByKey($key): ?Permission
    {
        // find by 'id' if type of $key is 'integer'
        if (is_int($key)) {
            return Permission::find($key);
        }

        // find by 'slug' or 'id' if type of $key is 'string'
        if (is_string($key)) {
            return Permission::where('id', $key)
                ->orWhere('slug', $key)
                ->first();
        }

        // return 'null' if type of $key is not 'Permission'
        if (!$key instanceof Permission) {
            return null;
        }

        // return first permission has id = $key->id
        return Permission::where('id', $key->id)->first();
    }

    /**
     * [getPermissionsExistInKeys description]
     * @param  [type] $keys [description]
     * @return [type]       [description]
     */
    public function getPermissionsExistInKeys(...$keys): Collection
    {
        $listPermissions = [];
        $keys            = collect($keys)->flatten();

        // add new permission into $listPermission if it exist
        foreach ($keys as $key) {
            $permission = $this->getPermissionByKey($key);

            if ($permission !== null) {
                $listPermissions[] = $permission;
            }
        }

        return collect($listPermissions)->unique();
    }

    /**
     * [hasPermission description]
     * @param  [type]  $key [description]
     * @return boolean      [description]
     */
    public function hasPermission($key): bool
    {
        $permission = $this->getPermissionByKey($key);

        if ($permission === null) {
            return false;
        }

        return $this->permissions->contains('id', $permission->id);
    }

    /**
     * [hasAllPermission description]
     * @param  [type]  $keys [description]
     * @return boolean       [description]
     */
    public function hasAllPermission(...$keys): bool
    {
        $keys = collect($keys)->flatten();

        foreach ($keys as $key) {
            if (!$this->hasPermission($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * [hasAnyPermission description]
     * @param  [type]  $keys [description]
     * @return boolean       [description]
     */
    public function hasAnyPermission(...$keys): bool
    {
        $keys = collect($keys)->flatten();

        foreach ($keys as $key) {
            if ($this->hasPermission($key)) {
                return true;
            }
        }

        return false;
    }
}
