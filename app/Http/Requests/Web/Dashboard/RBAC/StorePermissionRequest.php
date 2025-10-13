<?php

namespace App\Http\Requests\Web\Dashboard\RBAC;

use App\Models\Permission;
use App\Traits\GuardNameData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
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
    public function rules(): array
    {
        $guardList = array_map(fn($guard) => $guard['value'], $this->getGuardNameList());

        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-z.]+$/', 'not_in:all,administrator,admin', Rule::unique(Permission::class, 'name')],
            'guard_name' => ['required', 'string', 'min:2', 'max:255', Rule::in($guardList)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Name can only consist of lowercase alphabetical characters and dots.',
        ];
    }
}
