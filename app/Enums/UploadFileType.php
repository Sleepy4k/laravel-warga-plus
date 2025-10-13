<?php

namespace App\Enums;

use App\Foundations\Enum;

enum UploadFileType: string
{
    use Enum;

    // Case section started
    case FILE = 'files';
    case IMAGE = 'photos';
    case SETTING = 'settings';
    case AVATAR = 'avatar';

    // Custom section started
    case ACTIVITY = 'photos/activity';
    case ARTICLE = 'photos/article';
    case PRODUCT = 'photos/product';
    case DOCUMENT = 'files/document';
    case LETTER_ATTACHMENT = 'files/letter';
}
