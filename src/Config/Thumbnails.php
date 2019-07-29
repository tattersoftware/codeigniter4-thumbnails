<?php namespace Tatter\Thumbnails\Config;

use CodeIgniter\Config\BaseConfig;

class Thumbnails extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Default width to use when creating thumbnails
	public $width = 200;
	
	// Default width to use when creating thumbnails
	public $height = 200;
}
