<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Generate a new UUID for the model.
     *
     * @param string|null $keyname
     *
     * @return string
     */
    public static function generateUuid(?string $keyname): string
    {
        $uuid = null;

        do {
            $uuid = Str::uuid();
        } while (static::where($keyname ?? 'id', $uuid)->exists());

        return $uuid;
    }

    /**
     * Boot the UUID trait for the model.
     *
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = static::generateUuid($model->getKeyName());
            }
        });
    }
}
