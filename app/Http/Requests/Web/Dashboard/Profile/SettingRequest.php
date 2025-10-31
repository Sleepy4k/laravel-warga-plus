<?php

namespace App\Http\Requests\Web\Dashboard\Profile;

use App\Enums\FileUploaderType;
use App\Enums\Gender;
use App\Facades\FileUploader;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
        $fileUploader = FileUploader::init(FileUploaderType::PROFILE);
        $fileType = $fileUploader->get('type', 'image');
        $fileMimes = $fileUploader->get('mimes', 'jpeg,png,jpg');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        return [
            'phone' => ['required', 'string', 'min:6', 'max:50', 'regex:/^8[1-9][0-9]{6,15}$/', Rule::unique(User::class, 'phone')->ignore(auth('web')->id())],
            'first_name' => ['required', 'string', 'min:2', 'max:70'],
            'last_name' => ['required', 'string', 'min:2', 'max:70'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', Rule::in(Gender::toArray())],
            'job' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
        ];
    }
}
