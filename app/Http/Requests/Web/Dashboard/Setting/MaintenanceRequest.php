<?php

namespace App\Http\Requests\Web\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequest extends FormRequest
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
        return [
            'secret' => file_exists(storage_path('framework/down'))
                ? ['nullable', 'string', 'max:50']
                : ['required', 'string', 'max:50'],
        ];
    }
}
