<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Storage\FileUploaderManager;

/**
 * @method static self init(\App\Enums\FileUploaderType $type)
 * @method static string getFilePath()
 * @method static void store(mixed $fileContent)
 * @method static mixed get(string $key, string $default)
 *
 * @see \Modules\Storage\FileUploaderManager
 *
 * @mixins \Modules\Storage\FileUploaderManager
 */
class FileUploader extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FileUploaderManager::class;
    }
}
