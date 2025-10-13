<?php

namespace App\View\Components\Utils;

use App\Enums\ToasterType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Toast extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private ?bool $show = false,
        private ?ToasterType $type = ToasterType::INFO,
        private ?string $title = '',
        private ?string $message = '',
    ) {
        $this->show = Session::has('toast');
        $this->type = Session::get('toast.type');
        $this->title = Session::get('toast.title');
        $this->message = Session::get('toast.message');

        Session::forget('toast');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.utils.toast', [
            'show' => $this->show,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
        ]);
    }
}
