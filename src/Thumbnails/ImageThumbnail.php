<?php namespace Tatter\Thumbnails\Thumbnails;

use Config\Services;
use Tatter\Thumbnails\Interfaces\ThumbnailInterface;

class ImageThumbnail implements ThumbnailInterface
{	
	public function __construct($handler = null)
	{
		$this->factory = Services::image($handler);
	}

	public $extensions = ['jpg', 'jpeg', 'png', 'gif', 'xbm', 'xpm', 'wbmp', 'webp', 'bmp'];
	
	// Use the supplied CodeIgniter image factory to fit the image to its new size
	public function create(string $input, string $output, int $imageType, int $width, int $height): bool
	{		
		return $this->factory
			->withFile($input)
			->fit($width, $height, 'center')
			->convert($imageType)
			->save($output);
	}
}
