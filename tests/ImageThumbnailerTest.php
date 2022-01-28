<?php

use CodeIgniter\Files\File;
use Tatter\Thumbnails\Thumbnailers\ImageThumbnailer;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class ImageThumbnailerTest extends TestCase
{
    /**
     * The test file primed for use
     */
    protected File $file;

    protected function setUp(): void
    {
        parent::setUp();

        // Ready the cheeseburger
        $this->file = new File(SUPPORTPATH . 'assets/image.jpg');
    }

    public function testProcessCreatesFile()
    {
        $result = ImageThumbnailer::process($this->file, $this->config->imageType, $this->config->width, $this->config->height);

        $this->assertIsString($result);
        $this->assertFileExists($result);
    }
}
