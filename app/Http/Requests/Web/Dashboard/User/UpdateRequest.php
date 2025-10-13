<?php

namespace App\Http\Requests\Web\Dashboard\User;

use App\Models\Role;
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
        return [
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'telkom_batch' => ['required', 'string', 'max:5'],
            'is_active' => ['required', 'boolean'],
            'role' => ['required', 'string', 'max:255', Rule::exists(Role::class, 'name')],
            'whatsapp_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255']
        ];
    }
}
