<?php

namespace Tests\Support\Thumbnailers;

use CodeIgniter\Files\File;
use RuntimeException;
use Tatter\Thumbnails\Interfaces\ThumbnailerInterface;

class MockThumbnailer implements ThumbnailerInterface
{
    public static $didProcess  = false;
    public static $shouldError = false;

    public static function handlerId(): string
    {
        return 'mock';
    }

    public static function attributes(): array
    {
        return [
            'name'       => 'Mocker',
            'extensions' => '*',
        ];
    }

    /**
     * Blindly creates a file to match $imageType.
     */
    public function process(File $file, int $imageType, int $width, int $height): string
    {
        if (self::$shouldError) {
            throw new RuntimeException('This error happened.');
        }

        $path = tempnam(sys_get_temp_dir(), static::handlerId());

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
