<?php namespace Tatter\Thumbnails;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Tatter\Settings\Models\SettingModel;
use Tatter\Settings\Exceptions\SettingsException;

class Thumbnails
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Thumbnails\Config\Thumbnails
	 */
	protected $config;
	
	/**
	 * Array of supported extensions and their handlers
	 *
	 * @var array
	 */
	protected $handlers;
	
	
	// initiate library
	public function __construct(BaseConfig $config)
	{		
		// Save the configuration
		$this->config = $config;
		
		// Check for cached version of discovered handlers
		$this->handlers = cache('thumbnailHandlers');
	}
	
	// Reads a file and checks for a support handler to create the thumbnail
	public function create(string $input, string $output, int $imageType = IMAGETYPE_JPEG, int $width = null, int $height = null)
	{
		$this->ensureHandlers();
		
		// Check file extensions for a valid handler
		$extension = pathinfo($input, PATHINFO_EXTENSION);
		if (empty($this->handler)):
			$this->errors[] = lang('Thumbnails.noHandler', [$extension]);
			return false;
		endif;
	}
	
	// Check for all supported extensions and their handlers
	protected function ensureHandlers()
	{
		if (! is_null($this->handlers))
			return true;
		if ($cached = cache('thumbnailHandlers'))
			return true;
		
		$locator = Services::locator(true);

		// get all namespaces from the autoloader
		$namespaces = Services::autoloader()->getNamespace();
		
		// scan each namespace for thumbnail handlers
		$flag = false;
		foreach ($namespaces as $namespace => $paths):

			// get any files in /Thumbnails/ for this namespace
			$files = $locator->listNamespaceFiles($namespace, '/Thumbnails/');
			foreach ($files as $file):
			
				// skip non-PHP files
				if (substr($file, -4) !== '.php'):
					continue;
				endif;
				
				// get namespaced class name
				$name = basename($file, '.php');
				$class = $namespace . '\Thumbnails\\' . $name;
				
				include_once $file;

				// validate the class
				if (! class_exists($class, false))
					continue;
				$instance = new $class();
				
				// validate the property
				if (! isset($instance->extensions))
					continue;
				
				// register each supported extension
				foreach ($instance->extensions as $extension):
					if (empty($this->handlers[$extension])):
						$this->handlers[$extension] = [$class];
					else:
						$this->handlers[$extension][] = $class;
					endif;
				endforeach;
			endforeach;
		endforeach;
		
		// Cache the results
		cache()->save('thumbnailHandlers', $this->handlers, 300);
		
		return true;
	}
}
