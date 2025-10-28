<?php

namespace App\Http\Requests\Web\Auth;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomRegistrationRequest extends FormRequest
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
        $validRuleIn = ['on', 'off', true, false, '1', '0'];

        return [
            'first_name' => ['required', 'string', 'min:2', 'max:70'],
            'last_name' => ['required', 'string', 'min:2', 'max:70'],
            'gender' => ['required', 'string', 'max:10', Rule::in(Gender::toArray())],
            'birth_date' => ['required', 'date', 'before:today'],
            'job' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'agreement' => ['required', Rule::in($validRuleIn)],
            'privacy_policy' => ['required', Rule::in($validRuleIn)],
            'newsletter' => ['nullable', Rule::in($validRuleIn)],
        ];
    }
}
