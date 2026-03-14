<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaUrl
{
    public static function fromStoragePath(?string $path): ?string
    {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', trim($path)), '/');

        if (
            str_starts_with($normalizedPath, 'http://') ||
            str_starts_with($normalizedPath, 'https://') ||
            str_starts_with($normalizedPath, '//') ||
            str_starts_with($normalizedPath, 'data:')
        ) {
            return $normalizedPath;
        }

        if (str_starts_with($normalizedPath, 'storage/')) {
            self::ensurePublicStorageCopy(substr($normalizedPath, strlen('storage/')));

            return url($normalizedPath);
        }

        self::ensurePublicStorageCopy($normalizedPath);

        return url('storage/' . $normalizedPath);
    }

    public static function ensurePublicStorageCopy(?string $relativePath): void
    {
        if (!is_string($relativePath) || trim($relativePath) === '') {
            return;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', trim($relativePath)), '/');

        if ($normalizedPath === '' || str_contains($normalizedPath, '..')) {
            return;
        }

        $publicPath = public_path('storage/' . $normalizedPath);
        if (is_file($publicPath)) {
            return;
        }

        $disk = Storage::disk('public');
        if (!$disk->exists($normalizedPath)) {
            return;
        }

        $directory = dirname($publicPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        copy($disk->path($normalizedPath), $publicPath);
    }
}
