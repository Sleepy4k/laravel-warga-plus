<?php

namespace App\Http\Controllers\Web\Dashboard\Menu;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Menu\Navbar\OrderRequest;
use App\Http\Requests\Web\Dashboard\Menu\Navbar\StoreRequest;
use App\Http\Requests\Web\Dashboard\Menu\Navbar\UpdateRequest;
use App\Models\NavbarMenu;
use App\Services\Web\Dashboard\Menu\NavbarService;
use App\Traits\Authorizable;

class NavbarController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private NavbarService $service,
        private $policy = NavbarMenu::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
            'saveOrder' => 'update',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.menu.navbar.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create navbar.', 500);
        }

        return $this->sendResponse(null, 'Navbar created successfully.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, NavbarMenu $navbar)
    {
        if (!$this->service->update($request->validated(), $navbar)) {
            return $this->sendResponse(null, 'Failed to update navbar.', 500);
        }

        return $this->sendResponse(null, 'Navbar updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NavbarMenu $navbar)
    {
        if (!$this->service->destroy($navbar)) {
            return $this->sendResponse(null, 'Failed to delete navbar.', 500);
        }

        return $this->sendResponse(null, 'Navbar deleted successfully.', 200);
    }

    /**
     * Save the order of the navbar items.
     */
    public function saveOrder(OrderRequest $request)
    {
        $data = $request->validated();

        foreach ($data['order'] as $data) {
            NavbarMenu::where('id', $data['id'])->update(['order' => $data['order']]);
        }

        return $this->sendResponse(null, 'Navbar order saved successfully.', 200);
    }
}
