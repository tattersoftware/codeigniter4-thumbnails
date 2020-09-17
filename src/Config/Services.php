<?php namespace Tatter\Thumbnails\Config;

use CodeIgniter\Config\BaseService;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Thumbnails;

class Services extends BaseService
{
	/**
	 * Returns an instance of the Thumbnails library
	 * using the specified configuration.
	 *
	 * @param ThumbnailsConfig|null $config
	 * @param boolean $getShared
	 *
	 * @return Thumbnails
	 */
    public static function thumbnails(ThumbnailsConfig $config = null, bool $getShared = true): Thumbnails
	{
		if ($getShared)
		{
			return static::getSharedInstance('thumbnails', $config);
		}

		return new Thumbnails($config ?? config('Thumbnails'));
	}
}
