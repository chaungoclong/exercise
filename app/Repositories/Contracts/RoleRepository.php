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
     * Get a list of roles with its users sorted in descending order
     *
     * @return Collection
     */
    public function getListIndex(): Collection;


    /**
     * Get permission options for create || update Role
     *
     * @return array
     */
    public function getPermissionOptions(): array;


    /**
     * Create Role and Attach Permissions
     *
     * @param array $attributes
     * @param array $permissions
     * @return Role
     */
    public function createAndAttachPermissions(
        array $attributes,
        array $permissions
    ): Role;


    /**
     * Update and sync Permissions
     *
     * @param integer|string|Role $key
     * @param array $attributes
     * @param array $permissions
     * @return Role
     */
    public function updateAndSyncPermissions(
        int|string|Role $key,
        array $attributes,
        array $permissions
    ): Role;

    /**
     * Render HTML for Role
     *
     * @param Role $role
     * @return string
     */
    public function renderCardRole(Role $role): string;
}
