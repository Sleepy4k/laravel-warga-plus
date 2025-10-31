<?php

namespace App\Http\Requests\Web\Dashboard\Administration\Reference\Status;

use App\Models\LetterStatus;
use Illuminate\Container\Attributes\RouteParameter;
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
    public function rules(#[RouteParameter('status')] LetterStatus $status): array
    {
        return [
            'status' => ['required', 'string', 'max:100', Rule::unique(LetterStatus::class, 'status')->ignoreModel($status)]
        ];
    }
}
