<?php

namespace App\Repositories\Eloquent\Permission;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Facades\DataTables;

class EloquentPermissionRepository extends EloquentBaseRepository implements
    PermissionRepository
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * Convert Permission|Collection of Permission to array for Select2 Plugin
     *
     * @param Permission|Collection|null $data
     * @return array
     */
    public function convertToSelectOption(Permission|Collection|null $data): array
    {
        $options = [];

        if ($data === null) {
            return $options;
        }

        if ($data instanceof Collection) {
            foreach ($data as $permission) {
                $options[] = [
                    'id' => $permission->id,
                    'text' => $permission->name
                ];
            }

            return $options;
        }

        if ($data instanceof Permission) {
            $options[] = [
                'id' => $data->id,
                'text' => $data->name
            ];

            return $options;
        }
    }


    /**
     * Get all select options
     *
     * @return array
     */
    public function getAllSelectOptions(): array
    {
        return $this->convertToSelectOption($this->findAll());
    }


    public function datatables()
    {
        return DataTables::of($this->findAll())
            ->make(true);
    }
}
