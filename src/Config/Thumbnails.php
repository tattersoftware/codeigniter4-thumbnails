<?php

namespace Tatter\Thumbnails\Config;

use CodeIgniter\Config\BaseConfig;

class Thumbnails extends BaseConfig
{
    /**
     * Default width to use when creating thumbnails.
     *
     * @var int
     */
    public $width = 200;

    /**
     * Default height to use when creating thumbnails.
     *
     * @var int
     */
    public $height = 200;

    /**
     * Default output image type.
     *
     * @var int
     *
     * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
     */
    public $imageType = IMAGETYPE_JPEG;
}
