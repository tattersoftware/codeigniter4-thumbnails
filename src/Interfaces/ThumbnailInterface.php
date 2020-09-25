<?php namespace Tatter\Thumbnails\Interfaces;

use CodeIgniter\Files\File;
use Tatter\Handlers\Interfaces\HandlerInterface;

interface ThumbnailInterface extends HandlerInterface
{
	/**
	 * Creates a new thumbnail from a file.
	 *
	 * @param File    $file      The file that needs a thumbnail
	 * @param string  $output    Path to the output file
	 * @param integer $imageType A PHP imagetype constant, https://www.php.net/manual/en/function.image-type-to-mime-type.php
	 * @param integer $width     Width of the created thumbnail
	 * @param integer $height    Height of the created thumbnail
	 *
	 * @return boolean  Success or failure
	 */
	public function create(File $file, string $output, int $imageType, int $width, int $height): bool;
}
