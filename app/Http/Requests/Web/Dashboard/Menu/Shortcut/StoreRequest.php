<?php

namespace App\Http\Requests\Web\Dashboard\Menu\Shortcut;

use App\Models\Permission;
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
            'label' => ['required', 'string', 'max:30'],
            'route' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:100'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'max:255', Rule::exists(Permission::class, 'name')],
        ];
    }
}
