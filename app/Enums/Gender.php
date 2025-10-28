<?php

namespace App\Enums;

use App\Foundations\Enum;

enum Gender: string
{
    use Enum;

    // Case section started
    case Male = 'pria';
    case Female = 'wanita';
}
