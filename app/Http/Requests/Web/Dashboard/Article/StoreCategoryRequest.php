<?php

namespace App\Http\Requests\Web\Dashboard\Article;

use App\Models\ArticleCategory;
use App\Rules\CategoryLabel;
use App\Rules\CategoryName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:35', new CategoryName, Rule::unique(ArticleCategory::class, 'name')],
            'label' => ['required', 'string', 'max:75', new CategoryLabel],
        ];
    }
}
