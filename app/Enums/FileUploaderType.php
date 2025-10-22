<?php

namespace App\Enums;

use App\Foundations\Enum;

enum FileUploaderType: string
{
    use Enum;

    // Case section started
    case PROFILE = 'profile';
    case SETTING = 'setting';
    case DOCUMENT = 'document';
    case LETTER_TRANSACTION = 'document_transaction';
}
