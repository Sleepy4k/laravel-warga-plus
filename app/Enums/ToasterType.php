<?php

namespace App\Enums;

use App\Foundations\Enum;

enum ToasterType: string
{
    use Enum;

    // Case section started
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case DARK = 'dark';

    /**
     * Convert the value to the enum
     *
     * @param string $value
     * @return static
     */
    public static function toEnum(string $value): static
    {
        return match ($value) {
            'primary' => self::PRIMARY,
            'secondary' => self::SECONDARY,
            'success' => self::SUCCESS,
            'danger' => self::DANGER,
            'warning' => self::WARNING,
            'info' => self::INFO,
            'dark' => self::DARK,
            default => self::INFO,
        };
    }
}
