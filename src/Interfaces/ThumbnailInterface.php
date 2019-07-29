<?php namespace Tatter\Thumbnails\Interfaces;

interface ThumbnailInterface
{	
	public function create(string $input, string $output, int $imageType = IMAGETYPE_JPEG, int $width = null, int $height = null);
}
