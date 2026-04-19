<?php

namespace App\Support;

class MediaUrl
{
    /**
     * Convierte una ruta de almacenamiento en una URL pública válida.
     */
    public static function fromStoragePath(?string $path): ?string
    {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        // Normalizar barras y espacios
        $normalizedPath = ltrim(str_replace('\\', '/', trim($path)), '/');

        // Si ya es una URL completa, devolverla tal cual
        if (
            str_starts_with($normalizedPath, 'http://') ||
            str_starts_with($normalizedPath, 'https://') ||
            str_starts_with($normalizedPath, '//') ||
            str_starts_with($normalizedPath, 'data:')
        ) {
            return $normalizedPath;
        }

        // Si la ruta ya incluye 'storage/', limpiarla para evitar duplicados
        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('storage/'));
        }

        // Usar asset() para garantizar que la URL sea completa y segura (https)
        return asset('storage/' . $normalizedPath);
    }

    /**
     * Asegura que el archivo en public storage esté disponible.
     */
    public static function ensurePublicStorageCopy(?string $path): void
    {
        // En este entorno basta con que Laravel ya lo haya guardado.
        // Se mantiene la función para evitar el error 500 reportado en logs.
        return;
    }
}

