<?php

use CodeIgniter\Files\File;
use Tests\Support\ThumbnailsTestCase;
use Tatter\Thumbnails\Thumbnails\ImageThumbnail;

class ImageThumbnailTest extends ThumbnailsTestCase
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
		$handler = new ImageThumbnail();
		$output  = $this->root->url() . '/output.file';
		$result  = $handler->create($this->file, $output, $this->config->imageType, $this->config->width, $this->config->height);

		$this->assertTrue($result);
		$this->assertFileExists($output);
	}
}
