<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Agenda;

use App\DataTables\Administration\Agenda\OutgoingDataTable;
use App\Foundations\Controller;
use App\Services\Web\Dashboard\Administration\Agenda\OutgoingService;

class OutgoingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OutgoingDataTable $dataTable, OutgoingService $service)
    {
        return $dataTable->render('pages.dashboard.administration.agenda.outgoing.index', $service->invoke());
    }
}
