<?php

namespace App\Http\Controllers\Web\Dashboard\Setting;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Setting\UploaderRequest;
use App\Policies\Web\Setting\UploaderPolicy;
use App\Services\Web\Dashboard\Setting\UploaderService;
use App\Traits\Authorizable;

class UploaderController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private UploaderService $service,
        private $policy = UploaderPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.settings.uploader.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploaderRequest $request)
    {
        if (!$this->service->store($request->validated()))  {
            Toast::error("Something went wrong", "Failed to update uploader settings. Please try again later.");
            return back()->withInput();
        }

        Toast::primary("Success", "Uploader settings updated successfully.");

        return to_route('dashboard.settings.uploader.index');
    }
}
