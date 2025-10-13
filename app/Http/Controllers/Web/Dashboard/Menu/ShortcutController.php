<?php

namespace App\Http\Controllers\Web\Dashboard\Menu;

use App\DataTables\Menu\ShortcutDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Menu\Shortcut\StoreRequest;
use App\Http\Requests\Web\Dashboard\Menu\Shortcut\UpdateRequest;
use App\Models\Shortcut;
use App\Services\Web\Dashboard\Menu\ShortcutService;
use App\Traits\Authorizable;

class ShortcutController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ShortcutService $service,
        private $policy = Shortcut::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ShortcutDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.menu.shortcut.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create shortcut.', 500);
        }

        return $this->sendResponse(null, 'Shortcut created successfully.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Shortcut $shortcut)
    {
        if (!$this->service->update($request->validated(), $shortcut)) {
            return $this->sendResponse(null, 'Failed to update shortcut.', 500);
        }

        return $this->sendResponse(null, 'Shortcut updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut)
    {
        if (!$this->service->destroy($shortcut)) {
            return $this->sendResponse(null, 'Failed to delete shortcut.', 500);
        }

        return $this->sendResponse(null, 'Shortcut deleted successfully.', 200);
    }
}
