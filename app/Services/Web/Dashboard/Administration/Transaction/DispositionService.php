<?php

namespace App\Services\Web\Dashboard\Administration\Transaction;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Letter;
use App\Models\LetterDisposition;
use App\Models\LetterStatus;

class DispositionService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(Letter $letter): array
    {
        $letterUid = $letter->id;
        $search = request()->query('search', '');
        $letterStatuses = LetterStatus::select('id', 'status')->get();
        $data = LetterDisposition::where('letter_id', $letterUid)
            ->with('status')
            ->when($search, function ($query, $search) {
                $query->where('to', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            })
            ->latest('created_at')
            ->paginate(10)
            ->appends([
                'search' => $search
            ]);

        return compact('letterUid', 'letterStatuses', 'data', 'search');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Letter $letter, array $request): bool
    {
        try {
            LetterDisposition::create(array_merge($request, [
                'user_id' => auth('web')->id(),
                'letter_id' => $letter->id
            ]));

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create letter disposition: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Letter $letter, LetterDisposition $disposition): bool
    {
        try {
            $disposition->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update letter disposition: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter, LetterDisposition $disposition): bool
    {
        try {
            $disposition->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete letter disposition: ' . $th->getMessage(), [
                'disposition_id' => $disposition->id,
            ]);
            return false;
        }
    }
}
