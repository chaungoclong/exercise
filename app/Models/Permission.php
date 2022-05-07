<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * [roles description]
     * @return [type] [description]
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role',
            'permission_id',
            'role_id'
        );
    }


    /**
     * Undocumented function
     *
     * @return array
     */
    public function getSelectOptionAttribute(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->name
        ];
    }


    /**
     * Convert Permission|Collection of Permission|null to array of options
     *
     * @param Permission|Collection|null $input
     * @return array
     */
    public function convertToSelectOptions(Permission|Collection|null $input): array
    {
        $options = [];

        if (
            $input instanceof Collection
            && $input->first() instanceof Permission
        ) {
            $options = $input->map(
                fn ($permission) => $permission->select_option
            )->toArray();
        }

        if ($input instanceof Permission) {
            $options[] = $input->select_option;
        }

        return $options;
    }


    /**
     * Get all select options
     *
     * @return array
     */
    public function getAllSelectOptions(): array
    {
        return $this->convertToSelectOptions($this->get());
    }
}
