<?php

namespace Tatter\Thumbnails\Thumbnailers;

use CodeIgniter\Files\File;
use CodeIgniter\Images\Exceptions\ImageException;
use CodeIgniter\Images\Handlers\BaseHandler as ImagesHandler;
use Tatter\Thumbnails\Interfaces\ThumbnailerInterface;

/**
 * Crops and fits a thumbnail from an image
 */
class ImageThumbnailer implements ThumbnailerInterface
{
    /**
     * Image handler to use
     *
     * @var ImagesHandler
     */
    protected $images;

    public static function handlerId(): string
    {
        return 'image';
    }

    public static function attributes(): array
    {
        return [
            'name'       => 'Image',
            'extensions' => 'jpg,jpeg,png,gif,xbm,xpm,wbmp,webp,bmp',
        ];
    }

    /**
     * Accepts an explicit Image Manipulation Handler as optional injection
     *
     * @param ImagesHandler|string|null $imagesHandler Image handler, or classname for Config\Images::$handlers
     */
    public function __construct($imagesHandler = null)
    {
        $this->images = $imagesHandler instanceof ImagesHandler
            ? $imagesHandler
            : service('image', $imagesHandler);
    }

    public function getInterface(): string
    {
        return ThumbnailerInterface::class;
    }

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
    public function process(File $file, int $imageType, int $width, int $height): string
    {
        $path = tempnam(sys_get_temp_dir(), static::handlerId());

        $this->images
            ->withFile($file->getRealPath() ?: $file->__toString())
            ->fit($width, $height, 'center')
            ->convert($imageType)
            ->save($path);

        return $path;
    }
}
