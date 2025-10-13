<?php

namespace App\Enums;

use App\Foundations\Enum;

enum LetterType: string
{
    use Enum;

    // Case section started
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';
}
