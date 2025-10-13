<?php

namespace App\Http\Controllers\Web\Dashboard\Profile;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Profile\SettingRequest;
use App\Services\Web\Dashboard\Profile\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SettingService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.profile.setting', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request)
    {
        if (!$this->service->update($request->validated())) {
            Toast::danger('Error', 'An error occurred while updating the profile.');
            return back();
        }

        Toast::primary('Success', 'Profile updated successfully.');

        return to_route('profile.setting.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (!$request->input('confirm_delete')) {
            Toast::warning('Warning', 'Please confirm the deletion of your account.');
            return back();
        }

        try {
            $result = $this->service->delete();

            if (!$result) {
                Toast::danger('Error', 'An error occurred while deleting the account.');
                return back();
            }

            Toast::primary('Success', 'Account deleted successfully.');

            return to_route('login');
        } catch (\Throwable $th) {
            Toast::danger('Error', 'An error occurred while deleting the account.');
            return back();
        }
    }
}
