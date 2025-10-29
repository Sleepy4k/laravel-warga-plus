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
            'phone' => ['required', 'string', 'max:25', 'regex:/^8[1-9][0-9]{6,10}$/'],
            'identity_number' => ['required', 'string', 'max:25', 'regex:/^[0-9]{12,16}$/'],
            'other-phone' => ['nullable', 'string', 'max:25', 'regex:/^8[1-9][0-9]{6,10}$/'],
            'role' => ['required', 'string', 'max:255', Rule::exists(Role::class, 'name')],
        ];
    }
}
