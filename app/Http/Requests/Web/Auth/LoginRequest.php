<?php

namespace App\Http\Requests\Web\Auth;

use App\Rules\PhoneOrIdentity;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        return [
            'phone-identity' => ['required', 'string', 'min:10', 'max:20', new PhoneOrIdentity],
            'password' => ['required', 'string', 'min:8', 'max:20'],
        ];
    }
}
