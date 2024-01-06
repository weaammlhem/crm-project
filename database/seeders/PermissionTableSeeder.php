<?php

namespace Database\Seeders;

use App\Enum\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{

    public function run()
    {
        foreach (PermissionEnum::getValues() as $value) {
            Permission::create(['name' => $value, 'guard_name' => 'api']);
        }
    }

}
