<?php namespace Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/Thumbnails.php
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Thumbnails extends \Tatter\Thumbnails\Config\Files
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Default width to use when creating thumbnails
	public $width = 200;
	
	// Default width to use when creating thumbnails
	public $height = 200;
}
