<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepository extends BaseRepository
{
    /**
     * get role default
     * @return [NULL|Role] [description]
     */
    public function getDefault(): ?Role;


    /**
     * Create New Role
     *
     * @param array $payload
     * @return Role
     */
    public function createRole(array $payload): Role;



    /**
     * Update Role, Do Not Update 'slug' Attribute
     *
     * @param string|integer|Role $key
     * @param array $payload
     * @return Role
     */
    public function updateRole(
        string|int|Role $key,
        array $payload
    ): Role;


    /**
     * Delete Role
     *
     * @param integer|string|Role $key
     * @return boolean
     */
    public function deleteRole(int|string|Role $key): bool;


    /**
     * Force Delete Role
     *
     * @param integer|string|Role $key
     * @return void
     */
    public function forceDeleteRole(int|string|Role $key): bool;



    /**
     * Restore Role
     *
     * @param integer|string|Role $key
     * @return void
     */
    public function restoreRole(int|string|Role $key): bool;


    /**
     * Get Datatables Of Role
     *
     */
    public function datatables();
}
