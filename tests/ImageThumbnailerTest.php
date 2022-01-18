<?php

use CodeIgniter\Files\File;
use CodeIgniter\Images\Handlers\GDHandler;
use Tatter\Thumbnails\Thumbnailers\ImageThumbnailer;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class ImageThumbnailerTest extends TestCase
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

    public function testProcessCreatesFile()
    {
        $handler = new ImageThumbnailer();
        $result  = $handler->process($this->file, $this->config->imageType, $this->config->width, $this->config->height);

        $this->assertIsString($result);
        $this->assertFileExists($result);
    }

    public function testUsesHandler()
    {
        $images  = new GDHandler();
        $handler = new ImageThumbnailer($images);

        $result = $this->getPrivateProperty($handler, 'images');

        $this->assertSame($images, $result);
    }
}
