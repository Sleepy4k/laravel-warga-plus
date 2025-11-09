<?php

namespace App\Http\Requests\Web\Dashboard\Information;

use App\Models\InformationCategory;
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
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'string', 'max:50'],
            'new-category' => [Rule::requiredIf($this->input('category_id') === 'other'), 'max:50'],
        ];
    }
}
