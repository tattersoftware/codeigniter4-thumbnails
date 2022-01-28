<?php

namespace Tatter\Thumbnails\Thumbnailers;

use CodeIgniter\Files\File;
use CodeIgniter\Images\Exceptions\ImageException;
use CodeIgniter\Images\Handlers\BaseHandler as ImagesHandler;
use Tatter\Thumbnails\BaseThumbnailer;

/**
 * Crops and fits a thumbnail from an image
 */
class ImageThumbnailer extends BaseThumbnailer
{
    public const HANDLER_ID = 'image';
    public const EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'xbm', 'xpm', 'wbmp', 'webp', 'bmp'];

    /**
     * Uses a framework image handler to fit the image to its new size.
     *
     * @param File $file      The file that needs a thumbnail
     * @param int  $imageType A PHP imagetype constant, https://www.php.net/manual/en/function.image-type-to-mime-type.php
     * @param int  $width     Width of the created thumbnail
     * @param int  $height    Height of the created thumbnail
     *
     * @throws ImageException If the framework's ImagesHandler fails
     *
     * @return string Path to the newly-created file
     */
    public static function process(File $file, int $imageType, int $width, int $height): string
    {
        $image = service('image');
        $path  = tempnam(sys_get_temp_dir(), static::HANDLER_ID);

        $image->withFile($file->getRealPath() ?: $file->__toString())
            ->fit($width, $height, 'center')
            ->convert($imageType)
            ->save($path);

        return $path;
    }
}
