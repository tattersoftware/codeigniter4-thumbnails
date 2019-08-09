<?php namespace Tatter\Thumbnails\Thumbnails;

use Config\Services;
use Tatter\Handlers\Handlers\BaseHandler;
use Tatter\Thumbnails\Interfaces\ThumbnailInterface;

class ImageHandler extends BaseHandler implements ThumbnailInterface
{

	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Image',
		'uid'        => 'image',
		'summary'    => 'Crop and fit a thumbnail from an image',
		'extensions' => 'jpg,jpeg,png,gif,xbm,xpm,wbmp,webp,bmp',
	];

	// Accepts an explicit Image Manipulation Handler as optional injection
	public function __construct($handler = null)
	{
		$this->adapter = Services::image($handler);
	}
	
	// Use the supplied CodeIgniter image handler to fit the image to its new size
	public function create(string $input, string $output, int $imageType, int $width, int $height): bool
	{		
		return $this->adapter
			->withFile($input)
			->fit($width, $height, 'center')
			->convert($imageType)
			->save($output);
	}
}
