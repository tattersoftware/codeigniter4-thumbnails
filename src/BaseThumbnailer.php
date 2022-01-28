<?php

namespace Tatter\Thumbnails;

use CodeIgniter\Files\File;
use RuntimeException;

abstract class BaseThumbnailer
{
    public const HANDLER_ID = '';
    public const EXTENSIONS = [];

    /**
     * Creates a new thumbnail from a file.
     *
     * @param File $file      The file that needs a thumbnail
     * @param int  $imageType A PHP imagetype constant, https://www.php.net/manual/en/function.image-type-to-mime-type.php
     * @param int  $width     Width of the created thumbnail
     * @param int  $height    Height of the created thumbnail
     *
     * @throws RuntimeException on failure
     *
     * @return string Path to the newly-created file
     */
    abstract public static function process(File $file, int $imageType, int $width, int $height): string;
}
