<?php

namespace App\View\Components\Dashboard\Canvas;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * The type of footer to render.
     *
     * @var string
     */
    public string $footerType;

    /**
     * The allowed footer types.
     *
     * @var array<string>
     */
    protected array $allowedFooterTypes = [
        'create',
        'show',
        'edit',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct(string $type = 'create')
    {
        if (!in_array(strtolower($type), $this->allowedFooterTypes)) {
            $type = 'create';
        }

        $this->footerType = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.canvas.footer', [
            'type' => $this->footerType,
        ]);
    }
}
