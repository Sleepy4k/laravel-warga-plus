<?php

namespace App\Http\Requests\Web\Dashboard\Setting;

use App\Enums\FileUploaderType;
use App\Facades\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploaderRequest extends FormRequest
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
        $rules = [];
        $cases = FileUploaderType::cases();
        $typesOptions = Format::getFileUploadTypes();
        $mimesOptions = Format::getFileExtensions();
        $serverThreshold = Format::getServerMaxUploadSize();
        $maxSizeOptions = Format::uploadSizeOptions($serverThreshold);

        foreach ($cases as $case) {
            $rules[$case->value.'-type'] = ['required', 'string', Rule::in(array_keys($typesOptions))];
            $rules[$case->value.'-mimes'] = ['required', 'array'];
            $rules[$case->value.'-mimes.*'] = ['string', 'max:50', Rule::in(array_keys($mimesOptions))];
            $rules[$case->value.'-max_size'] = ['required', 'integer', 'min:1', 'max:'.$serverThreshold, Rule::in(array_keys($maxSizeOptions))];
        }

        return $rules;
    }
}
