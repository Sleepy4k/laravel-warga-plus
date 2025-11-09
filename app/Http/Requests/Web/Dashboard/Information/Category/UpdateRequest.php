<?php

namespace App\Http\Requests\Web\Dashboard\Information\Category;

use App\Models\InformationCategory;
use Illuminate\Container\Attributes\RouteParameter;
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
    public function rules(#[RouteParameter('category')] InformationCategory $category): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique(InformationCategory::class, 'name')->ignoreModel($category)],
        ];
    }
}
