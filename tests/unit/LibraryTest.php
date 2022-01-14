<?php

use Tests\Support\ThumbnailsTestCase;
use Tatter\Thumbnails\Thumbnails;

class LibraryTest extends ThumbnailsTestCase
{
	/**
	 * Path to the test file
	 *
	 * @var string
	 */
	protected $input = SUPPORTPATH . 'assets/image.jpg';

	/**
	 * Convenient virtual output path
	 *
	 * @var string
	 */
	protected $output;

	protected function setUp(): void
	{
		parent::setUp();

		$this->output = $this->root->url() . '/output.file';
	}

	public function testCreatesFile()
	{
		$this->thumbnails->create($this->input, $this->output);

		$this->assertFileExists($this->output);
	}
}
