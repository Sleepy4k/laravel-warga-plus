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
            'phone' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,15}$/', Rule::unique(User::class, 'phone')],
            'identity_number' => ['required', 'string', 'regex:/^[1-9][0-9]{15,19}$/', Rule::unique(User::class, 'identity_number')],
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
            'phone' => 'Phone',
            'identity_number' => 'Identity Number',
            'password' => 'Password',
        ];
    }
}
