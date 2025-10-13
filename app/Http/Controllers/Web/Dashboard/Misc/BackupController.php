<?php

namespace App\Http\Controllers\Web\Dashboard\Misc;

use App\DataTables\Misc\BackupDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Misc\BackupRequest;
use App\Policies\Web\Misc\BackupPolicy;
use App\Services\Web\Dashboard\Misc\BackupService;
use App\Traits\Authorizable;

class BackupController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private BackupService $service,
        private $policy = BackupPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(BackupDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.misc.backup.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BackupRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to perform backup operation.', 500);
        }

        return $this->sendResponse(null, 'Backup operation completed successfully.', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $date)
    {
        $file = $this->service->show($date);
        if (empty($file)) {
            abort(404, 'Backup file not found');
        }

        return response()->stream(function () use ($file) {
            echo $file['file'];
        }, 200, [
            'Content-Type' => $file['type'],
            'Content-Disposition' => 'attachment; filename="' . $file['name'] . '"',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $date)
    {
        if (!$this->service->destroy($date)) {
            return $this->sendResponse(null, 'Failed to delete backup file.', 500);
        }

        return $this->sendResponse(null, 'Backup file deleted successfully.', 200);
    }
}
