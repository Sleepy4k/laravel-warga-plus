<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Notification\Toaster;

/**
 * @method static void show(string $title, string $message, string|ToasterType $type)
 * @method static void info(string $title, string $message)
 * @method static void success(string $title, string $message)
 * @method static void warning(string $title, string $message)
 * @method static void danger(string $title, string $message)
 * @method static void primary(string $title, string $message)
 * @method static void secondary(string $title, string $message)
 * @method static void error(string $title, string $message)
 * @method static void dark(string $title, string $message)
 *
 * @see \Modules\Notification\Toaster
 *
 * @mixins \Modules\Notification\Toaster
 */
class Toast extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Toaster::class;
    }
}
