<?php

namespace App\Http\Requests\Install;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * The route that users should be redirected to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute  = 'install.user';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:195'],
            'username' => ['required', 'string', 'min:6', 'max:50', 'regex:/^[a-zA-Z0-9]+$/', Rule::unique(User::class, 'username')],
            'email' => ['required', 'email', 'max:100', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', 'min:8', 'max:65', 'confirmed'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
