<?php

namespace App\Http\Requests\Web\Dashboard\RBAC;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\GuardNameData;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    use GuardNameData;

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
    public function rules(#[RouteParameter('role')] Role $role): array
    {
        $guardList = array_map(fn($guard) => $guard['value'], $this->getGuardNameList());

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(Role::class, 'name')->ignoreModel($role)],
            'guard_name' => [ 'required', 'string', 'max:255', Rule::in($guardList)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists(Permission::class, 'name')],
        ];
    }
}
