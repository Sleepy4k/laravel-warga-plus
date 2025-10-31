<?php

namespace App\Http\Requests\Web\Dashboard\RBAC;

use App\Models\Permission;
use App\Traits\GuardNameData;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
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
    public function rules(#[RouteParameter('permission')] Permission $permission): array
    {
        $guardList = array_map(fn($guard) => $guard['value'], $this->getGuardNameList());

        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-z.]+$/', Rule::notIn(['all', 'administrator', 'admin']), Rule::unique(Permission::class, 'name')->ignoreModel($permission)],
            'guard_name' => ['required', 'string', 'min:2', 'max:255', Rule::in($guardList)],
        ];
    }
}
