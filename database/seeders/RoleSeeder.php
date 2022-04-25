<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config          = config('rolesystem.roles_structure');
        $roleDefaultSlug = config('rolesystem.default', '');

        if ($config === null) {
            $this->command->error("Config not found");
            $this->command->line('');

            return false;
        }

        $mapAbilities = config('rolesystem.abilities_map');

        // truncate table before seed
        if (config('rolesystem.truncate_before_seed', false)) {
            $this->truncateBeforeSeed();
        }

        foreach ($config as $slug => $modules) {
            // create new role
            $role = Role::firstOrCreate([
                'name'           => ucwords(str_replace('_', ' ', $slug)),
                'slug'           => $slug,
                'is_default'     => ($slug === $roleDefaultSlug),
                'is_user_define' => false
            ]);

            $permissions = [];

            // create permissions of this role
            foreach ($modules as $module => $abilities) {
                $abilities = trimStringArray(explode(',', $abilities));

                foreach ($abilities as $ability) {
                    $abilityName = $mapAbilities[$ability] ?? '';

                    $permissions[] = Permission::firstOrCreate([
                        'name' => ucwords($abilityName) . ' ' . ucwords($module),
                        'slug' => $module . '_' . $abilityName
                    ]);

                    $this->command->info(
                        'Creating Permission to ' . $abilityName . ' for '
                        . $module
                    );
                }

                $role->attachPermission($permissions);
            }

            // seed user for this role
            if (config('rolesystem.seed_user', false)) {
                $this->command->info('Creating User for Role: ' . $role->name);

                $user = User::firstOrCreate([
                    'email' => strtolower($role->name) . '@gmail.com',
                    'password' => Hash::make('11111111')
                ]);

                $user->assignRole($role);
            }
        }
    }

    /**
     * clear all data before seed
     * @return [type] [description]
     */
    public function truncateBeforeSeed()
    {
        $this->command->info('Truncating User, Role and Permission tables');

        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();
    }
}
