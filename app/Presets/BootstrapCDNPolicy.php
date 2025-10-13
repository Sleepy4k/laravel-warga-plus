<?php

namespace App\Presets;;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class BootstrapCDNPolicy implements Preset
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
                ->add([Directive::STYLE_ELEM], 'maxcdn.bootstrapcdn.com');
        } else {
            $policy
                ->add([Directive::STYLE], 'maxcdn.bootstrapcdn.com');
        }
    }
}
