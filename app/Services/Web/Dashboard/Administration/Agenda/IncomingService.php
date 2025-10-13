<?php

namespace App\Services\Web\Dashboard\Administration\Agenda;

use App\Foundations\Service;

class IncomingService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(): array
    {
        $columns = [
            'letter_date',
            'received_date',
            'created_at'
        ];

        return compact('columns');
    }
}
