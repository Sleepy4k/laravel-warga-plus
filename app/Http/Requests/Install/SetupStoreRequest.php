<?php

namespace App\Http\Requests\Install;

use Illuminate\Foundation\Http\FormRequest;

class SetupStoreRequest extends FormRequest
{
    /**
     * The route that users should be redirected to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute  = 'install.setup';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'app_url' => ['required', 'url', 'max:195'],
            'app_name' => ['required', 'string', 'max:195'],
            'db_host' => ['required', 'string', 'max:195'],
            'db_port' => ['required', 'string', 'max:195'],
            'db_name' => ['required', 'string', 'max:195'],
            'db_user' => ['required', 'string', 'max:195'],
            'db_pass' => ['nullable', 'string', 'max:195'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_url' => 'App URL',
            'app_name' => 'App Name',
            'database_hostname' => 'Database Hostname',
            'database_port' => 'Database Port',
            'database_name' => 'Database Name',
            'database_username' => 'Database Username',
            'database_password' => 'Database Password',
        ];
    }
}
