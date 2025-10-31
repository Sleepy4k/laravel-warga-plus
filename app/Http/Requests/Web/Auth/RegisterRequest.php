<?php

namespace App\Http\Requests\Web\Auth;

use App\Enums\Gender;
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
            'phone' => ['required', 'string', 'min:6', 'max:50', 'regex:/^8[1-9][0-9]{6,15}$/'],
            'identity_number' => ['required', 'string', 'min:12', 'max:16', 'regex:/^[0-9]{12,16}$/'],
            'first_name' => ['required', 'string', 'min:2', 'max:70'],
            'last_name' => ['required', 'string', 'min:2', 'max:70'],
            'gender' => ['required', 'string', 'max:10', Rule::in(Gender::toArray())],
            'birth_date' => ['required', 'date', 'before:today'],
            'job' => ['required', 'string', 'max:100'],
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
            'phone.regex' => 'Phone number must be a valid Indonesian phone number.',
            'identity_number.regex' => 'Identity number must be numeric and contain 12 to 16 digits.',
        ];
    }
}
