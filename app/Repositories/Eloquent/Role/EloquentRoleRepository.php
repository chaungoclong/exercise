<?php

namespace App\Repositories\Eloquent\Role;

use App\Models\Role;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class EloquentRoleRepository extends EloquentBaseRepository implements
    RoleRepository
{
    private PermissionRepository $permissionRepository;

    private const VIEW_RENDER_CARD_ROLE = 'components.cards.role-card';

    public function __construct(
        Role $model,
        PermissionRepository $permissionRepository
    ) {
        $this->model = $model;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * [getDefault description]
     * @return [NULL|Role] [description]
     */
    public function getDefault(): ?Role
    {
        return $this->model->getDefault();
    }


    /**
     * Get all Role with users and count users
     *
     * @return Collection
     */
    public function getListIndex(): Collection
    {
        return $this->model
            ->with('users')
            ->withCount('users')
            ->orderBy('created_at')
            ->get();
    }


    /**
     * Get permission options for create || update Role
     *
     * @return array
     */
    public function getPermissionOptions(): array
    {
        $permissionOptions = [];

        foreach ($this->permissionRepository->findAll() as $permission) {
            $permissionOptions[] = [
                'id' => $permission->id,
                'text' => $permission->name
            ];
        }

        return $permissionOptions;
    }

    /**
     * Create and attach Permission
     *
     * @param array $attributes
     * @param array $permissions
     * @return Role
     */
    public function createAndAttachPermissions(
        array $attributes,
        array $permissions
    ): Role {
        $role = $this->model->create($attributes);

        $role->attachPermission($permissions);

        $role = $this->findById($role->id, ['users'], ['users']);

        return $role;
    }

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
    ): Role {
        $role = null;

        if ($key instanceof Role) {
            $role = $key;
        } else {
            $role = $this->findById($key);
        }

        $role->update($attributes);

        $role->syncPermission($permissions);

        $role = $this->findById($role->id, ['users'], ['users']);

        return $role;
    }


    /**
     * Render HTML for Role
     *
     * @param Role $role
     * @return string
     */
    public function renderCardRole(Role $role): string
    {
        return view(self::VIEW_RENDER_CARD_ROLE, ['role' => $role])
            ->render();
    }
}
