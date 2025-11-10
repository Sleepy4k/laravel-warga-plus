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

    /**
     * Get the icon associated with the report type.
     */
    public function icon(): string
    {
        return match ($this) {
            ReportType::CREATED => 'bx-plus-circle',
            ReportType::PROCCESSED => 'bx bx-time-five',
            ReportType::COMPLETED => 'bx bx-check-circle',
            ReportType::DENIED => 'bx bx-x-circle',
        };
    }

    /**
     * Get the label associated with the report type.
     */
    public function label(): string
    {
        return match ($this) {
            ReportType::CREATED => 'Dibuat',
            ReportType::PROCCESSED => 'Diproses',
            ReportType::COMPLETED => 'Selesai',
            ReportType::DENIED => 'Ditolak',
        };
    }

    /**
     * Get the background color associated with the report type.
     */
    public function bgColor(): string
    {
        return match ($this) {
            ReportType::CREATED => 'primary',
            ReportType::PROCCESSED => 'warning',
            ReportType::COMPLETED => 'success',
            ReportType::DENIED => 'danger',
        };
    }
}
