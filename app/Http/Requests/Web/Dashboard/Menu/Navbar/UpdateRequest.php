<?php

namespace App\Http\Requests\Web\Dashboard\Menu\Navbar;

use App\Models\Permission;
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
        $routeList = getWebRoutes();

        return [
            'name' => ['required', 'string', 'max:30'],
            'is_spacer' => ['required', 'boolean'],
            'is_sortable' => ['required', 'boolean'],
            'icon' => ['nullable', 'string', 'max:15'],
            'route' => ['nullable', 'string', 'max:55', Rule::in($routeList)],
            'active_routes' => ['nullable', 'string', 'max:255'],
            'parameters' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'max:255', Rule::exists(Permission::class, 'name')],
        ];
    }
}
