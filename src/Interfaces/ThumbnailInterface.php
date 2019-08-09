<?php namespace Tatter\Thumbnails\Interfaces;

use Tatter\Handlers\Interfaces\HandlerInterface;

interface ThumbnailInterface extends HandlerInterface
{	
	public function create(string $input, string $output, int $imageType, int $width, int $height): bool;
}
