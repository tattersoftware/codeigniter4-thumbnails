<?php namespace Tatter\Thumbnails\Config;

use CodeIgniter\Config\BaseConfig;

class Handlers extends BaseConfig
{
	// Directory to search across namespaces for supported handlers
	public $directory = 'Thumbnails';
	
	// Model used to track handlers
	public $model = '\Tatter\Thumbnails\Models\ThumbnailModel';
}
