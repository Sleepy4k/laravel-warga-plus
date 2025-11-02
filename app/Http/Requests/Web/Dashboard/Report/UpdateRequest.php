<?php

namespace App\Http\Requests\Web\Dashboard\Report;

use App\Enums\FileUploaderType;
use App\Enums\ReportType;
use App\Facades\FileUploader;
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

        $rules = [
            'title' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(ReportType::toArray())],
            'category_id' => ['required', 'string', 'max:50'],
            'new-category' => [Rule::requiredIf($this->input('category_id') === 'other'), 'max:50'],
            'file' => ['nullable', 'array'],
            'file.*' => ['required', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
        ];

        if (isUserHasRole(config('rbac.role.default'))) {
            unset($rules['status']);
        }

        return $rules;
    }
}
