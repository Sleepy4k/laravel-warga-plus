<?php

namespace App\View\Composers;

use Illuminate\View\View;

class BreadcrumbComposer
{
    /**
     * List of breadcrumbs.
     *
     * @var array
     */
    protected array $breadcrumbs = [];

    /**
     * List of not allowed last parts in the breadcrumb.
     *
     * @var array
     */
    protected array $notAllowedLastParts = ['index', 'create', 'edit'];

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $routeName = request()->route()?->getName() ?? 'dashboard.index';
        $parts = explode('.', $routeName);
        $lastIndex = count($parts) - 1;

        foreach ($parts as $index => $part) {
            if ($index === $lastIndex && in_array($part, $this->notAllowedLastParts, true)) {
                continue;
            }

            $this->breadcrumbs[] = [
                'name' => ucfirst(str_replace('_', ' ', $part)),
            ];
        }

        $view->with('breadcrumbs', $this->breadcrumbs);
    }
}
