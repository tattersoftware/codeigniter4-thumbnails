<?php namespace Tatter\Thumbnails\Interfaces;

interface ThumbnailInterface
{	
	public function create(string $input, string $output, int $imageType, int $width, int $height): bool;
}
