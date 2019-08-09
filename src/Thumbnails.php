<?php namespace Tatter\Thumbnails;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Tatter\Thumbnails\Models\ThumbnailModel;

class Thumbnails
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Thumbnails\Config\Thumbnails
	 */
	protected $config;
	
	/**
	 * Pre-loaded model.
	 *
	 * @var \Tatter\Thumbnails\Models\ThumbnailModel
	 */
	protected $model;
	
	/**
	 * Array error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors;
	
	
	// initiate library
	public function __construct(BaseConfig $config)
	{		
		// Save the configuration and model
		$this->config = $config;
		$this->model  = new ThumbnailModel();
	}
	
	// Return any error messages
	public function getErrors()
	{
		return $this->errors;
	}
	
	// Reads a file and checks for a supported handler to create the thumbnail
	public function create(string $input, string $output)
	{
		// Check file extension for a valid handler
		$extension = pathinfo($input, PATHINFO_EXTENSION);
		$handlers = $this->model->getForExtension($extension);
		
		// No handlers matched?
		if (empty($handlers)):
			$this->errors[] = lang('Thumbnails.noHandler', [$extension]);
			return false;
		endif;
		
		// Try each supported handler until one succeeds
		foreach ($handlers as $handler):
			$instance = new $handler->class();
			$result = $instance->create($input, $output, $this->config->imageType, $this->config->width, $this->config->height);
			if ($result):
				break;
			endif;
		endforeach;
		
		// Check for failure
		if (! $result):
			$this->errors[] = lang('Thumbnails.handlerFail', [$input]);
			return false;
		endif;
		
		// Verify the output
		if (exif_imagetype($output) != $this->config->imageType):
			$this->errors[] = lang('Thumbnails.createFailed', [$input]);
			return false;
		endif;
		
		return true;
	}
	
	// Set the output image type
	// e.g. https://www.php.net/manual/en/function.image-type-to-mime-type.php
	public function setImageType(int $imageType)
	{
		$this->config->imageType = $imageType;
	}
	
	// Set the output image width
	public function setWidth(int $width)
	{
		$this->config->width = $width;
	}
	
	// Set the output image height
	public function setHeight(int $height)
	{
		$this->config->height = $height;
	}
}
