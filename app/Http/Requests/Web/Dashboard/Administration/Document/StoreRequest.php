<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Document;

use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        $fileUploader = FileUploader::init(FileUploaderType::DOCUMENT);
        $fileType = $fileUploader->get('type', 'file');
        $fileMimes = $fileUploader->get('mimes', 'pdf,doc,docx');
        $fileMaxSize = $fileUploader->get('max_size', 10240);

        return [
            'title' => ['required', 'string', 'max:100', Rule::unique(Document::class, 'title')],
            'category_id' => ['required', 'string', 'max:36', Rule::exists(DocumentCategory::class, 'id')],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_archived' => ['required', 'boolean'],
            'file' => ['required', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
        ];
    }
}
