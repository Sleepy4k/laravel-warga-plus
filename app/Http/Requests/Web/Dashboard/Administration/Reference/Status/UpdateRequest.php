<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Reference\Status;

use App\Models\LetterStatus;
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
            'status' => ['required', 'string', 'max:100', Rule::unique(LetterStatus::class, 'status')->ignore($this->route('status')->id)]
        ];
    }
}
