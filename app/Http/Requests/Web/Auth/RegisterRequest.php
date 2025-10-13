<?php

namespace App\Http\Requests\Web\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validRuleIn = ['on', 'off', true, false, '1', '0'];

        return [
            'username' => ['required', 'string', 'min:6', 'max:50', 'regex:/^[a-zA-Z0-9]+$/', Rule::unique(User::class, 'username')],
            'email' => ['required', 'email', 'max:100', Rule::unique(User::class, 'email')],
            'first_name' => ['required', 'string', 'min:2', 'max:70'],
            'last_name' => ['required', 'string', 'min:2', 'max:70'],
            'whatsapp_number' => ['required', 'string', 'max:15'],
            'telkom_batch' => ['required', 'string', 'max:5'],
            'address' => ['required', 'string', 'max:255'],
            'agreement' => ['required', Rule::in($validRuleIn)],
            'privacy_policy' => ['required', Rule::in($validRuleIn)],
            'newsletter' => ['nullable', Rule::in($validRuleIn)],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.regex' => 'Username can only consist of alphanumeric characters and numbers.',
        ];
    }
}
