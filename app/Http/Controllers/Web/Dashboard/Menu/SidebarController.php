<?php

namespace App\Http\Controllers\Web\Dashboard\Menu;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Menu\Sidebar\OrderRequest;
use App\Http\Requests\Web\Dashboard\Menu\Sidebar\StoreRequest;
use App\Http\Requests\Web\Dashboard\Menu\Sidebar\UpdateRequest;
use App\Models\Menu;
use App\Services\Web\Dashboard\Menu\SidebarService;
use App\Traits\Authorizable;

class SidebarController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SidebarService $service,
        private $policy = Menu::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
            'saveOrder' => 'saveOrder',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.menu.sidebar.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create sidebar.', 500);
        }

        return $this->sendResponse(null, 'Sidebar created successfully.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Menu $sidebar)
    {
        if (!$this->service->update($request->validated(), $sidebar)) {
            return $this->sendResponse(null, 'Failed to update sidebar.', 500);
        }

        return $this->sendResponse(null, 'Sidebar updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $sidebar)
    {
        if (!$this->service->destroy($sidebar)) {
            return $this->sendResponse(null, 'Failed to delete sidebar.', 500);
        }

        return $this->sendResponse(null, 'Sidebar deleted successfully.', 200);
    }

    /**
     * Save the order of the sidebar items.
     */
    public function saveOrder(OrderRequest $request)
    {
        $data = $request->validated();

        foreach ($data['order'] as $data) {
            Menu::where('id', $data['id'])->update(['order' => $data['order']]);
        }

        return $this->sendResponse(null, 'Sidebar order saved successfully.', 200);
    }
}
