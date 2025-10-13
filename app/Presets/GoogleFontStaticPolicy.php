<?php

namespace App\Presets;;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleFontStaticPolicy implements Preset
{
    /**
     * Configure csp policies for general and other policy
     *
     * @return void
     */
    public function configure(Policy $policy): void
    {
        if (app()->environment(['production', 'staging'])) {
            $policy
                ->add([Directive::STYLE_ELEM], 'fonts.gstatic.com')
                ->add([Directive::FONT], 'fonts.gstatic.com');
        } else {
            $policy
                ->add([Directive::STYLE], 'fonts.gstatic.com')
                ->add([Directive::FONT], 'fonts.gstatic.com');
        }
    }
}
