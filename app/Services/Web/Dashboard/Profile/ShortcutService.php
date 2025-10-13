<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Shortcut;

class ShortcutService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $shortcuts = Shortcut::getAllAccessible();
        $userShortcuts = auth('web')->user()->shortcuts;

        return compact('shortcuts', 'userShortcuts');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Shortcut $shortcut): int
    {
        try {
            $user = auth('web')->user();
            if ($user->shortcuts->contains($shortcut->id)) return 2;

            $user->shortcuts()->attach($shortcut->id);

            return 1;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update shortcut: ' . $th->getMessage(), [
                'shortcut_id' => $shortcut->id,
            ]);
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut): bool
    {
        try {
            $user = auth('web')->user();
            if (!$user->shortcuts->contains($shortcut->id)) return false;

            $user->shortcuts()->detach($shortcut->id);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete shortcut: ' . $th->getMessage(), [
                'shortcut_id' => $shortcut->id,
            ]);
            return false;
        }
    }
}
