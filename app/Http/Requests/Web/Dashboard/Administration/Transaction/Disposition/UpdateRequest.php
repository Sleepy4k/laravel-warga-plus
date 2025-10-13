<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Transaction\Disposition;

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
            'to' => ['required', 'string', 'max:100'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'letter_status_id' => ['required', 'string', Rule::exists(LetterStatus::class, 'id')],
            'content' => ['required', 'string'],
            'note' => ['nullable', 'string']
        ];
    }
}
