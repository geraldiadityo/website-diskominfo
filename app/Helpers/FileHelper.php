<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Format file size in bytes to human-readable string.
     */
    public static function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf('%.1f %s', $bytes / pow(1024, $factor), $units[(int) $factor] ?? 'TB');
    }
}
