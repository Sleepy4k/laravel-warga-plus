<?php

namespace App\View\Components\Landing;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * The page navigation menus.
     *
     * @var array
     */
    private array $pages = [];

    private array $usefulLinks = [];

    private array $relatedWebsites = [];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pages = [
            [
                'name' => 'Home',
                'route' => 'landing.home',
            ],
            [
                'name' => 'Tentang Kami',
                'route' => 'cookie.policy',
            ],
            [
                'name' => 'Laporan',
                'route' => 'cookie.policy',
            ],
            [
                'name' => 'Informasi RT',
                'route' => 'cookie.policy',
            ],
        ];

        $this->usefulLinks = [
            [
                'name' => 'Privacy Policy',
                'route' => 'privacy.policy',
            ],
            [
                'name' => 'Terms of Service',
                'route' => 'tos.policy',
            ],
        ];

        $this->relatedWebsites = [
            [
                'name' => 'Telkom University Purwokerto',
                'url' => 'https://purwokerto.telkomuniversity.ac.id',
            ],
        ];
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.landing.footer', [
            'pages' => $this->pages,
            'usefulLinks' => $this->usefulLinks,
            'relatedWebsites' => $this->relatedWebsites,
        ]);
    }
}
