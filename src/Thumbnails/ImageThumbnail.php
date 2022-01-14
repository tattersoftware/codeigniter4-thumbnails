<?php

namespace Tatter\Thumbnails\Thumbnails;

use CodeIgniter\Files\File;
use CodeIgniter\Images\Exceptions\ImageException;
use CodeIgniter\Images\Handlers\BaseHandler as ImagesHandler;
use Config\Services;
use Tatter\Handlers\BaseHandler;
use Tatter\Thumbnails\Interfaces\ThumbnailInterface;

/**
 * Crops and fits a thumbnail from an image
 */
class ImageThumbnail extends BaseHandler implements ThumbnailInterface
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string> Must include keys: name, extensions
	 */
	public $attributes = [
		'name'       => 'Image',
		'extensions' => 'jpg,jpeg,png,gif,xbm,xpm,wbmp,webp,bmp',
	];

	/**
	 * Image handler to use
	 *
	 * @var ImagesHandler
	 */
	public $images;

	/**
	 * Accepts an explicit Image Manipulation Handler as optional injection
	 *
	 * @param ImagesHandler|string|null $imagesHandler Image handler, or name for Config/Images::$handlers
	 */
	public function __construct($imagesHandler = null)
	{
		$this->images = $imagesHandler instanceof ImagesHandler
			? $imagesHandler
			: Services::image($imagesHandler);
	}

	/**
	 * Uses a framework image handler to fit the image to its new size.
	 *
	 * @param File   $file      The file that needs a thumbnail
	 * @param string $output    Path to the output file
	 * @param int    $imageType A PHP imagetype constant, https://www.php.net/manual/en/function.image-type-to-mime-type.php
	 * @param int    $width     Width of the created thumbnail
	 * @param int    $height    Height of the created thumbnail
	 *
	 * @return bool Success or failure
	 */
	public function create(File $file, string $output, int $imageType, int $width, int $height): bool
	{
		try {
			return $this->images
			    ->withFile($file->getRealPath() ?: $file->__toString())
			    ->fit($width, $height, 'center')
			    ->convert($imageType)
			    ->save($output);
		} catch (ImageException $e)
		{
			return false;
		}
	}
}
