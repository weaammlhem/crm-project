<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageTableSeeder extends Seeder
{
    public function run()
    {
        Stage::create([
            'project_id' => Project::first()->id,
            'name' => 'ToDo',
            'color' => '#FF0000',
        ]);

        Stage::create([
            'project_id' => Project::first()->id,
            'name' => 'In Progress',
            'color' => '#FFA500',
        ]);

        Stage::create([
            'project_id' => Project::first()->id,
            'name' => 'Review',
            'color' => '#00FF00',
        ]);

        Stage::create([
            'project_id' => Project::first()->id,
            'name' => 'Complete',
            'color' => '#0000FF',
        ]);
    }

}
