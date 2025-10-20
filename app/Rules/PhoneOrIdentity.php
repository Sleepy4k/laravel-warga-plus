<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneOrIdentity implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $identityPattern = '/^[0-9]{12,16}$/';
        $phonePattern = '/^(\+62|62|0)8[1-9][0-9]{6,10}$/';

        if (!preg_match($phonePattern, $value) && !preg_match($identityPattern, $value)) {
            $fail('The :attribute must be a valid Indonesian phone number or identity number.');
        }
    }
}
