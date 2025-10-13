<?php

namespace App\Http\Requests\Web\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

class SecurityRequest extends FormRequest
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
            'current_password' => ['required', 'string', 'min:8', 'max:65'],
            'password' => ['nullable', 'string', 'min:8', 'max:65', 'confirmed'],
        ];
    }
}
