<?php

namespace Tatter\Thumbnails\Factories;

use Tatter\Handlers\BaseFactory;
use Tatter\Thumbnails\BaseThumbnailer;

/**
 * Thumbnailer Factory Class
 *
 * Used to discover all compatible Thumbnailers.
 *
 * @method static class-string<BaseThumbnailer>   find(string $id)
 * @method static class-string<BaseThumbnailer>[] findAll()
 */
class ThumbnailerFactory extends BaseFactory
{
    public const HANDLER_PATH = 'Thumbnailers';
    public const HANDLER_TYPE = BaseThumbnailer::class;

    /**
     * Gathers attributes for all Thumbnailers that support the given extension.
     *
     * @return class-string<BaseThumbnailer>[]
     */
    public static function findForExtension(string $extension): array
    {
        $specific  = [];
        $universal = [];

        foreach (self::findAll() as $thumbnailer) {
            if (in_array($extension, $thumbnailer::EXTENSIONS, true)) {
                $specific[$thumbnailer::HANDLER_ID] = $thumbnailer;
            } elseif ($thumbnailer::EXTENSIONS === ['*']) {
                $universal[$thumbnailer::HANDLER_ID] = $thumbnailer;
            }
        }

        // Always return extension-specific handlers first
        return array_merge($specific, $universal);
    }
}
