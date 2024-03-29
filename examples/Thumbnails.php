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

class Thumbnails extends \Tatter\Thumbnails\Config\Thumbnails
{
    /**
     * Default width to use when creating thumbnails.
     */
    public int $width = 200;

    /**
     * Default height to use when creating thumbnails.
     */
    public int $height = 200;

    /**
     * Default output image type.
     *
     * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
     */
    public int $imageType = IMAGETYPE_JPEG;
}
