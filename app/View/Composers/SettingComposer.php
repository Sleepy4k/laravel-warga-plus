<?php

namespace App\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class SettingComposer
{
    /**
     * The settings data.
     *
     * @var array
     */
    protected array $settings;

    /**
     * Create a new profile composer.
     */
    public function __construct() {
        if (!empty($this->settings)) return;

        $this->settings = Setting::allAsKeyValue();
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $view->with('appSettings', $this->settings);
    }
}
