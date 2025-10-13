<?php

namespace App\Enums;

use App\Foundations\Enum;

enum SitemapChangeFreqEnum: string
{
    use Enum;

    // Case section started
    case ALWAYS = 'always';
    case HOURLY = 'hourly';
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case NEVER = 'never';
}
