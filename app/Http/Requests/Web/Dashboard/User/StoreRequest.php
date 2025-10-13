<?php

namespace App\Http\Requests\Web\Dashboard\User;

use App\Models\Role;
use App\Models\User;
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
        return [
            'username' => ['required', 'string', 'max:25', Rule::unique(User::class, 'username')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'other-email' => ['nullable', 'string', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255', Rule::exists(Role::class, 'name')],
        ];
    }
}
