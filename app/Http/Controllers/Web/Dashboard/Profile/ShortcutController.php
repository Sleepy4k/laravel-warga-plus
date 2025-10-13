<?php

namespace App\Http\Controllers\Web\Dashboard\Profile;

use App\Foundations\Controller;
use App\Models\Shortcut;
use App\Policies\Web\Profile\UserShortcutPolicy;
use App\Services\Web\Dashboard\Profile\ShortcutService;
use App\Traits\Authorizable;

class ShortcutController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ShortcutService $service,
        private $policy = UserShortcutPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.profile.shortcut', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Shortcut $shortcut)
    {
        $result = $this->service->update($shortcut);

        if ($result === 0) {
            return $this->sendResponse(null, 'Failed to update permission. Please try again.', 500);
        }

        if ($result === 2) {
            return $this->sendResponse(null, 'Permission already exists.', 409);
        }

        return $this->sendResponse($shortcut, 'Permission successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut)
    {
        if (!$this->service->destroy($shortcut)) {
            return $this->sendResponse(null, 'Failed to delete permission. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Permission successfully deleted.', 200);
    }
}
