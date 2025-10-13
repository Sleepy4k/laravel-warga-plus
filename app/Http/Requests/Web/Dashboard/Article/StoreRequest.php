<?php

namespace App\Http\Requests\Web\Dashboard\Article;

use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use App\Models\Article;
use App\Models\ArticleCategory;
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
        $fileUploader = FileUploader::init(FileUploaderType::ARTICLE);
        $fileType = $fileUploader->get('type', 'image');
        $fileMimes = $fileUploader->get('mimes', 'jpeg,png,jpg');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        return [
            'title' => ['required', 'string', 'max:100', Rule::unique(Article::class, 'title')],
            'categories' => ['required', 'array'],
            'categories.*' => ['string', 'max:36', Rule::exists(ArticleCategory::class, 'id')],
            'image' => ['required', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
        ];
    }
}
