<?php

namespace App\Http\Requests\Web\Dashboard\Setting;

use App\Models\Setting;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
    public function rules(#[RouteParameter('appSettingType')] string $settingType): array
    {
        $validationData = Setting::getValidationData();
        $rules = [];

        foreach ($validationData as $key => $rule) {
            if (str_starts_with($key, $settingType . '_')) {
                $rules[$key] = $rule;
            }
        }

        $rules['tab'] = ['nullable', 'string'];

        if ($settingType === 'footer') {
            $rules['link'] = ['nullable', 'array'];
            $rules['link.*.key'] = ['nullable', 'string', 'max:255'];
            $rules['link.*.title'] = ['required', 'string', 'max:255'];
            $rules['link.*.url'] = ['required', 'string', 'max:255', 'url'];
        }

        return $rules;
    }
}
