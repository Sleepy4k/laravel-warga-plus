<?php

namespace App\Enums;

use App\Foundations\Enum;

enum ReportType: string
{
    use Enum;

    // Case section started
    case CREATED = 'dibuat';
    case PROCCESSED = 'diproses';
    case COMPLETED = 'selesai';
    case DENIED = 'ditolak';
}
