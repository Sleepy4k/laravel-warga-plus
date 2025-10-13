<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Transaction;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Transaction\Disposition\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Transaction\Disposition\UpdateRequest;
use App\Models\Letter;
use App\Models\LetterDisposition;
use App\Policies\Web\Administration\Transaction\DispositionPolicy;
use App\Services\Web\Dashboard\Administration\Transaction\DispositionService;
use App\Traits\Authorizable;

class DispositionController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DispositionService $service,
        private $policy = DispositionPolicy::class,
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
    public function index(Letter $letter)
    {
        return view('pages.dashboard.administration.transaction.disposition.index', $this->service->index($letter));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Letter $letter)
    {
        if (!$this->service->store($letter, $request->validated())) {
            return $this->sendResponse(null, 'Failed to create letter disposition. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter disposition successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Letter $letter, LetterDisposition $disposition)
    {
        if (!$this->service->update($request->validated(), $letter, $disposition)) {
            return $this->sendResponse(null, 'Failed to update letter disposition. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter disposition successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter, LetterDisposition $disposition)
    {
        if (!$this->service->destroy($letter, $disposition)) {
            return $this->sendResponse(null, 'Failed to delete letter disposition. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Letter disposition successfully deleted.', 200);
    }
}
