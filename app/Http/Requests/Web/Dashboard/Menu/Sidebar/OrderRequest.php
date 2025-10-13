<?php

namespace App\Http\Requests\Web\Dashboard\Menu\Sidebar;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        $totalMenus = Menu::count();

        return [
            'order' => ['required', 'array'],
            'order.*.parent_id' => ['nullable', 'string', 'max:36', Rule::exists(Menu::class, 'id')],
            'order.*.id' => ['required', 'string', 'max:36', Rule::exists(Menu::class, 'id')],
            'order.*.order' => ['required', 'integer', 'min:0', 'max:'.$totalMenus],
        ];
    }
}
