<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Thumbnails;
use Tests\Support\Thumbnailers\MockThumbnailer;

/**
 * @internal
 */
abstract class TestCase extends CIUnitTestCase
{
    /**
     * @var ThumbnailsConfig
     */
    protected $config;

    /**
     * @var Thumbnails
     */
    protected $thumbnails;

    /**
     * Path to the test file
     *
     * @var string
     */
    protected $input = SUPPORTPATH . 'assets/image.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        // Disable handler caching
        config('Handlers')->cacheDuration = null;

        // Create the library
        $this->config     = new ThumbnailsConfig();
        $this->thumbnails = new Thumbnails($this->config);

        MockThumbnailer::$shouldError = false;
        MockThumbnailer::$didProcess  = false;
    }
}
