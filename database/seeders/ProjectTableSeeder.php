<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Stage;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run()
    {
        Project::create([
            'team_id' => Team::first()->id,
            'title' => 'CRM Project',
            'description' => 'Test',
            'start_date' => '2023-01-01',
            'end_date' => '2024-05-01',
        ]);
    }

}
