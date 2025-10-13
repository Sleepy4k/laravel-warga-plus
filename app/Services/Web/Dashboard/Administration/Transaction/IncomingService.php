<?php

namespace App\Services\Web\Dashboard\Administration\Transaction;

use App\Enums\LetterType;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Letter;
use App\Models\LetterAttachment;
use App\Models\LetterClassification;
use Illuminate\Support\Facades\DB;

class IncomingService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $search = request()->query('search', null);
        $classifications = LetterClassification::select('id', 'name')->get();
        $data = Letter::where('type', LetterType::INCOMING)
            ->with([
                'classification:id,code,name',
                'attachments:id,letter_id,path,file_name,file_size,extension',
                'dispositions:id,letter_id,letter_status_id,user_id,to,due_date,content,note'
            ])
            ->when($search, function ($query, $search) {
                $query->where('reference_number', 'like', "%{$search}%")
                    ->orWhere('agenda_number', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%");
            })
            ->latest('letter_date')
            ->paginate(10)
            ->appends([
                'search' => $search
            ]);

        return compact('data', 'search', 'classifications');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $user = auth('web')->user();

            $attachments = $request['file'] ?? [];
            unset($request['file']);

            DB::beginTransaction();

            $letter = Letter::create(array_merge($request, [
                'type' => LetterType::INCOMING,
                'user_id' => $user->id,
            ]));

            if ($attachments && !empty($attachments)) {
                foreach ($attachments as $attachment) {
                    LetterAttachment::create([
                        'path' => $attachment,
                        'file_name' => $attachment->getClientOriginalName(),
                        'file_size' => $attachment->getSize(),
                        'extension' => $attachment->getClientOriginalExtension(),
                        'letter_id' => $letter->id,
                        'user_id' => $user->id
                    ]);
                }
            }

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create incoming letter: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Letter $letter): bool
    {
        try {
            $user = auth('web')->user();

            $attachments = $request['file'] ?? [];
            unset($request['file']);

            DB::beginTransaction();

            $letter->update($request);

            if ($attachments && !empty($attachments)) {
                foreach ($attachments as $attachment) {
                    LetterAttachment::create([
                        'path' => $attachment,
                        'file_name' => $attachment->getClientOriginalName(),
                        'file_size' => $attachment->getSize(),
                        'extension' => $attachment->getClientOriginalExtension(),
                        'letter_id' => $letter->id,
                        'user_id' => $user->id
                    ]);
                }
            }

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update incoming letter: ' . $th->getMessage(), [
                'request' => $request,
                'letter_id' => $letter->id
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter): bool
    {
        try {
            if ($letter->attachments()->exists()) {
                foreach ($letter->attachments as $attachment) {
                    $attachment->delete();
                }
            }

            $letter->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete incoming letter: ' . $th->getMessage(), [
                'letter_id' => $letter->id
            ]);
            return false;
        }
    }
}
