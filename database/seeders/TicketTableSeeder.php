<?php

namespace Database\Seeders;

use App\Enum\TicketStatusEnum;
use App\Enum\TicketTypeEnum;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketTableSeeder extends Seeder
{
    public function run()
    {
        Ticket::create([
            'title' => 'My laptop down',
            'summary' => 'When i run phpstorm my laptop is breaking',
            'type' => TicketTypeEnum::TECHNICAL,
            'status' => TicketStatusEnum::PENDING,
            'user_id' => User::where('email', '=', 'lama@weaam.com')->first()->id
        ]);

        Ticket::create([
            'title' => 'الماوس تبعي خربانة',
            'summary' => 'وقعت معي انا وعم درس لما ووئام من كتر ما جننوني',
            'type' => TicketTypeEnum::GENERAL,
            'status' => TicketStatusEnum::SOLVED,
            'user_id' => User::where('email', '=', 'lama@weaam.com')->first()->id
        ]);

        Ticket::create([
            'title' => 'الضو منزوع',
            'summary' => 'عم يشعل ويطفي',
            'type' => TicketTypeEnum::ADMINISTRATIVE,
            'status' => TicketStatusEnum::UNSOLVED,
            'user_id' => User::where('email', '=', 'lama@weaam.com')->first()->id
        ]);

        Ticket::create([
            'title' => 'الضو منزوع',
            'summary' => 'عم يشعل ويطفي',
            'type' => TicketTypeEnum::ADMINISTRATIVE,
            'status' => TicketStatusEnum::UNSOLVED,
            'user_id' => User::where('email', '=', 'test@lama.com')->first()->id
        ]);
    }
}
