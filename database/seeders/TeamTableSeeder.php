<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamTableSeeder extends Seeder
{

    public function run()
    {
        Team::create([
            'name' => 'Tech Team',
            'description' => 'Tech Team for tech problem',
            'max_number' => 20,
        ]);
    }

}
