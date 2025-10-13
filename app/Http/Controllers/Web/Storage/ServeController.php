<?php

namespace App\Http\Controllers\Web\Storage;

use App\Foundations\Controller;

class ServeController extends Controller
{
    /**
     * Blocked file names.
     */
    protected static array $blockedFiles = [
        '.gitignore', '.env', '.htaccess', 'composer.json', 'composer.lock',
    ];

    /**
     * Blocked file extensions.
     */
    protected static array $blockedExtensions = [
        'php', 'env', 'json', 'lock', 'htaccess', 'git', 'md', 'yml', 'yaml',
    ];

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $path)
    {
        $type = config('filesystems.default') == 'local' ? 'private' : 'public';
        $safePath = str_replace(['../', '..\\'], '', $path);
        $fullPath = storage_path("app/$type/$safePath");

        $realBase = realpath(storage_path("app/$type"));
        $realFile = realpath($fullPath);

        $basename = strtolower(basename($realFile));
        $extension = strtolower(pathinfo($realFile, PATHINFO_EXTENSION));

        if (in_array($basename, self::$blockedFiles) || in_array($extension, self::$blockedExtensions)) {
            abort(403, 'Forbidden: Access to this file is not allowed');
        }

        if (!$realFile || strpos($realFile, $realBase) !== 0) {
            abort(403, 'Forbidden: Invalid file path');
        }

        if (!is_file($realFile) || !is_readable($realFile)) {
            abort(404, 'File not found or not accessible');
        }

        return response()->file($realFile, [
            'Content-Type' => mime_content_type($realFile),
            'Content-Disposition' => 'inline; filename="' . basename($realFile) . '"',
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
}
