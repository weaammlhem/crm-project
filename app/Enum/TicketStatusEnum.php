<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

class TicketStatusEnum extends Enum
{
    const PENDING = 1;

    const UNSOLVED = 2;

    const SOLVED = 3;
}
