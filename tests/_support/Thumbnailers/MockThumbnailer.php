<?php

namespace Tests\Support\Thumbnailers;

use CodeIgniter\Files\File;
use RuntimeException;
use Tatter\Thumbnails\BaseThumbnailer;

class MockThumbnailer extends BaseThumbnailer
{
    public const HANDLER_ID = 'mock';
    public const EXTENSIONS = ['*'];

    public static $didProcess  = false;
    public static $shouldError = false;

    /**
     * Blindly creates a file to match $imageType.
     */
    public static function process(File $file, int $imageType, int $width, int $height): string
    {
        if (self::$shouldError) {
            throw new RuntimeException('This error happened.');
        }

        $path = tempnam(sys_get_temp_dir(), static::HANDLER_ID);

        switch ($imageType) {
            case IMAGETYPE_JPEG: $extension = 'jpg'; break;

            case IMAGETYPE_PNG: $extension = 'png'; break;

            default: $extension = '';
        }

        copy(SUPPORTPATH . 'assets/thumbnail.' . $extension, $path);
        self::$didProcess = true;

        return $path;
    }
}
