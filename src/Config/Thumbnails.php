<?php

namespace Tatter\Thumbnails\Config;

use CodeIgniter\Config\BaseConfig;

class Thumbnails extends BaseConfig
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
