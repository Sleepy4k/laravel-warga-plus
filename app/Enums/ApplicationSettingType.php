<?php

namespace App\Enums;

use App\Foundations\Enum;

enum ApplicationSettingType: string
{
    use Enum;

    // Case section started
    case APP = 'app';
    case SEO = 'seo';
    case SIDEBAR = 'sidebar';
    case FOOTER = 'footer';
}
