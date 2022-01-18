<?php

namespace Tatter\Thumbnails\Factories;

use Tatter\Handlers\BaseFactory;
use Tatter\Thumbnails\Interfaces\ThumbnailerInterface;

/**
 * Thumbnailer Factory Class
 *
 * Used to discover all compatible Thumbnailers.
 */
class ThumbnailerFactory extends BaseFactory
{
    public const RETURN_TYPE = ThumbnailerInterface::class;

    /**
     * Returns the search path.
     */
    public function getPath(): string
    {
        return 'Thumbnailers';
    }
}
