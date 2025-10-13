<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrUsername implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strpos($value, '@') !== false) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $fail('The email address must be a valid email format.');
            }
        } else {
            if (!preg_match('/^[a-zA-Z0-9]{6,50}$/', $value)) {
                $fail('The username must be alphanumeric and between 6 to 50 characters long.');
            }
        }
    }
}
