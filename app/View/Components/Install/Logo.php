<?php

namespace App\View\Components\Install;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Logo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $logo,
        private ?string $divClasses,
        private ?string $imgClasses,
        private ?string $textClasses,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.install.logo', [
            'divClasses' => "flex items-center gap-2 ".$this->divClasses,
            'imgClasses' => $this->imgClasses ? 'h-[6vh] w-auto '.$this->imgClasses : 'h-[6vh] w-auto',
            'textClasses' => $this->textClasses ?? 'text-white',
        ]);
    }
}
