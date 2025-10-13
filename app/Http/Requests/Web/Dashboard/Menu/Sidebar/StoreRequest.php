<?php

namespace App\Http\Requests\Web\Dashboard\Menu\Sidebar;

use App\Models\Menu;
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
        $routeList = getWebRoutes();
        $parentMenus = Menu::select('id', 'name', 'order', 'parent_id')
            ->whereNull('parent_id')
            ->where('is_spacer', false)
            ->get()
            ->pluck('id')
            ->toArray();

        return [
            'name' => ['required', 'string', 'max:30'],
            'is_spacer' => ['required', 'boolean'],
            'is_children' => ['required', 'boolean'],
            'is_sortable' => ['required', 'boolean'],
            'parent_id' => ['nullable', 'string', Rule::in($parentMenus)],
            'icon' => ['nullable', 'string', 'max:15'],
            'route' => ['nullable', 'string', 'max:55', Rule::in($routeList)],
            'active_routes' => ['nullable', 'string', 'max:255'],
            'parameters' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'max:255', Rule::exists(Permission::class, 'name')],
        ];
    }
}
