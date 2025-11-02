<?php

namespace App\Http\Requests\Web\Dashboard\Report\Progress;

use App\Models\Report;
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
            'report_id' => ['required', 'string', Rule::exists(Report::class, 'id')],
            'title' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}
