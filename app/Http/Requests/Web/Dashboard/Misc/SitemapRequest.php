<?php

namespace App\Http\Requests\Web\Dashboard\Misc;

use App\Enums\SitemapChangeFreqEnum;
use Illuminate\Foundation\Http\FormRequest;

class SitemapRequest extends FormRequest
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
        $changeFrequencies = array_map(fn($case) => $case->value, SitemapChangeFreqEnum::cases());

        return [
            'url' => ['required', 'string', 'url', 'max:255'],
            'last_modified' => ['required', 'string', 'date_format:Y-m-d H:i'],
            'change_frequency' => ['required', 'string', 'in:'.implode(',', $changeFrequencies)],
            'priority' => ['required', 'numeric', 'between:0,1'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }
}
