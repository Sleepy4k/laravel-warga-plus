<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Reference\Classification;

use App\Models\LetterClassification;
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
            'name' => ['required', 'string', 'max:150', Rule::unique(LetterClassification::class, 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
