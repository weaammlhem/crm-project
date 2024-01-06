<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{

    public function run()
    {
        $role = Role::create(['name' => 'super-admin','guard_name' => 'api']);
        $allPermissions = Permission::all();
        $role->givePermissionTo($allPermissions);
    }

}
