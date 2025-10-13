<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Transaction;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Transaction\Outgoing\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Transaction\Outgoing\UpdateRequest;
use App\Models\Letter;
use App\Policies\Web\Administration\Transaction\OutgoingPolicy;
use App\Services\Web\Dashboard\Administration\Transaction\OutgoingService;
use App\Traits\Authorizable;

class OutgoingController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private OutgoingService $service,
        private $policy = OutgoingPolicy::class,
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
    public function index()
    {
        return view('pages.dashboard.administration.transaction.outgoing.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create letter. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Letter $letter)
    {
        if (!$this->service->update($request->validated(), $letter)) {
            return $this->sendResponse(null, 'Failed to update letter. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter)
    {
        if (!$this->service->destroy($letter)) {
            return $this->sendResponse(null, 'Failed to delete letter. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter successfully deleted.', 200);
    }
}
