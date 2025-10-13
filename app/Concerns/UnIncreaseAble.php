<?php

namespace App\Concerns;

trait UnIncreaseAble
{
    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the data type of the primary key ID.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
