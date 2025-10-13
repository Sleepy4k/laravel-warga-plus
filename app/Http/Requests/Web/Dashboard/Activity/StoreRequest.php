<?php

namespace App\Http\Requests\Web\Dashboard\Activity;

use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use App\Models\Activity;
use App\Models\ActivityCategory;
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
        $fileUploader = FileUploader::init(FileUploaderType::ACTIVITY);
        $fileType = $fileUploader->get('type', 'image');
        $fileMimes = $fileUploader->get('mimes', 'jpeg,png,jpg');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(Activity::class, 'name')],
            'description' => ['required', 'string'],
            'image' => ['required', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
            'category_id' => ['required', 'string', 'max:36', Rule::exists(ActivityCategory::class, 'id')],
        ];
    }
}
