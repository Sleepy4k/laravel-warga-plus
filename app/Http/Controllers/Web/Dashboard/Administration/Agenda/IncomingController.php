<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Agenda;

use App\DataTables\Administration\Agenda\IncomingDataTable;
use App\Foundations\Controller;
use App\Services\Web\Dashboard\Administration\Agenda\IncomingService;

class IncomingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IncomingDataTable $dataTable, IncomingService $service)
    {
        return $dataTable->render('pages.dashboard.administration.agenda.incoming.index', $service->invoke());
    }
}
