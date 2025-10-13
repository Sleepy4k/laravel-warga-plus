<?php

namespace App\View\Components\Auth\Register;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonalInfo extends Component
{
    /**
     * The years available for selection.
     *
     * @var array
     */
    private array $years;

    /**
     * Create a new component instance.
     */
    public function __construct(public bool $isCustomRegistration = false)
    {
        $years = array_reverse(range(2002, date('Y')));
        $years = array_combine($years, $years);
        $this->years = $years;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auth.register.personal-info', [
            'years' => $this->years,
        ]);
    }
}
