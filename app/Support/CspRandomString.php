<?php

namespace App\Support;

use Illuminate\Support\Str;
use Spatie\Csp\Nonce\NonceGenerator;

class CspRandomString implements NonceGenerator
{
    /**
     * Generate csp no once key
     *
     * @return string
     */
    public function generate(): string
    {
        $appName = config('app.name');
        $hashed = strtolower(Str::limit(str_replace(['/', '+', '='], '', encrypt($appName)), 16, ''));

        return Str::random(16) . $hashed . Str::random(16);
    }
}
