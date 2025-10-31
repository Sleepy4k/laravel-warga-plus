<?php

namespace App\Http\Requests\Web\Dashboard\Misc;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BackupRequest extends FormRequest
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
            'type' => ['required', 'string', 'min:3', 'max:25', Rule::in(['backup', 'cleanup'])],
        ];
    }
}
