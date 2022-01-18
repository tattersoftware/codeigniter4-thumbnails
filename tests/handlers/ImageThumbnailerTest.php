<?php

use CodeIgniter\Files\File;
use Tatter\Thumbnails\Thumbnailers\ImageThumbnailer;
use Tests\Support\ThumbnailsTestCase;

/**
 * @internal
 */
final class ImageThumbnailerTest extends ThumbnailsTestCase
{
    /**
     * The test file primed for use
     *
     * @var File
     */
    protected $file;

    protected function setUp(): void
    {
        parent::setUp();

        // Ready the cheeseburger
        $this->file = new File(SUPPORTPATH . 'assets/image.jpg');
    }

    public function testCreatesFile()
    {
        $handler = new ImageThumbnailer();
        $result  = $handler->process($this->file, $this->config->imageType, $this->config->width, $this->config->height);

        $this->assertIsString($result);
        $this->assertFileExists($result);
    }
}
