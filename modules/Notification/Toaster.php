<?php

namespace Modules\Notification;

use App\Enums\ToasterType;
use Illuminate\Support\Facades\Session;

class Toaster
{
    /**
     * Show the toast.
     *
     * @param string $title
     * @param string $message
     * @param string|ToasterType $type
     */
    public function show(string $title, string $message, string|ToasterType $type)
    {
        if (!$type instanceof ToasterType) {
            $type = ToasterType::toEnum($type);
        }

        Session::put('toast', [
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    /**
     * Show the toast with info type.
     *
     * @param string $title
     * @param string $message
     */
    public function info(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::INFO);
    }

    /**
     * Show the toast with success type.
     *
     * @param string $title
     * @param string $message
     */
    public function success(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::SUCCESS);
    }

    /**
     * Show the toast with warning type.
     *
     * @param string $title
     * @param string $message
     */
    public function warning(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::WARNING);
    }

    /**
     * Show the toast with danger type.
     *
     * @param string $title
     * @param string $message
     */
    public function danger(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::DANGER);
    }

    /**
     * Show the toast with error type.
     *
     * @param string $title
     * @param string $message
     */
    public function error(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::DANGER);
    }

    /**
     * Show the toast with primary type.
     *
     * @param string $title
     * @param string $message
     */
    public function primary(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::PRIMARY);
    }

    /**
     * Show the toast with secondary type.
     *
     * @param string $title
     * @param string $message
     */
    public function secondary(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::SECONDARY);
    }

    /**
     * Show the toast with dark type.
     *
     * @param string $title
     * @param string $message
     */
    public function dark(string $title, string $message)
    {
        $this->show($title, $message, ToasterType::DARK);
    }
}
