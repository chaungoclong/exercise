<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PermissionRepository extends BaseRepository
{
    /**
     * Convert Permission|Collection of Permission to array for Select2 Plugin
     *
     * @param Permission|Collection|null $data
     * @return array
     */
    public function convertToSelectOption(Permission|Collection|null $data): array;


    /**
     * Get all select options
     *
     * @return array
     */
    public function getAllSelectOptions(): array;


    /**
     * Get DataTables Of Permissions
     *
     */
    public function datatables();
}
