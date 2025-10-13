<?php

namespace App\Enums;

use App\Foundations\Enum;

enum UserOnlineStatus: string
{
    use Enum;

    // Case section started
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case AWAY = 'away';
    case BUSY = 'busy';
}
