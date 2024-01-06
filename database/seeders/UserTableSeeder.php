<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Enum\UserType;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'team_id' => Team::first()->id,
            'name' => 'Lama',
            'email' => 'lama@weaam.com',
            'password' => Hash::make('12345678'),
            'address' => 'Syria, Damascus',
            'age' => '2000-08-01',
            'phone' => '+963934922354',
            'gender' => 1,
            'specialize' => 'Back-end',
            'type' => UserType::SUPER_ADMIN,
        ]);
        $user->assignRole(RoleEnum::SUPER_ADMIN);

        User::create([
            'team_id' => Team::first()->id,
            'name' => 'Test',
            'email' => 'test@lama.com',
            'password' => Hash::make('12345678'),
            'address' => 'Syria, Damascus',
            'age' => 12,
            'phone' => '+963934922354',
            'gender' => 2,
            'specialize' => 'Front-end',
            'type' => UserType::EMPLOYEE,
        ]);
    }

}
