<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Transaction\Outgoing;

use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use App\Models\Letter;
use App\Models\LetterClassification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fileUploader = FileUploader::init(FileUploaderType::LETTER_TRANSACTION);
        $fileType = $fileUploader->get('type', 'file');
        $fileMimes = $fileUploader->get('mimes', 'pdf,doc,docx,jpg,jpeg,png');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        return [
            'reference_number' => ['required', 'string', 'max:100', Rule::unique(Letter::class, 'reference_number')->ignore($this->route('letter')->id)],
            'agenda_number' => ['required', 'string', 'max:100'],
            'to' => ['required', 'string', 'max:100'],
            'letter_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'note' => ['nullable', 'string'],
            'classification_id' => ['required', Rule::exists(LetterClassification::class, 'id')],
            'file' => ['nullable', 'array'],
            'file.*' => ['nullable', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
        ];
    }
}
