<?php

namespace App\Http\Requests\Web\Dashboard\Product;

use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use App\Models\ProductCategory;
use App\Models\Product;
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
        $fileUploader = FileUploader::init(FileUploaderType::PRODUCT);
        $fileType = $fileUploader->get('type', 'image');
        $fileMimes = $fileUploader->get('mimes', 'jpeg,png,jpg');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(Product::class, 'name')->ignore($this->route('product')->id)],
            'description' => ['required', 'string', 'max:500'],
            'category_id' => ['required', 'string', 'max:36', Rule::exists(ProductCategory::class, 'id')],
            'price' => ['required', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'is_available' => ['nullable', 'boolean'],
            'image' => ['nullable', $fileType, 'mimes:'.$fileMimes, 'extensions:'.$fileMimes, 'max:'.$fileMaxSize],
            'location' => ['nullable', 'string'],
        ];
    }
}
