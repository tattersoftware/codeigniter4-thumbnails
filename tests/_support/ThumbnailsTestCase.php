<?php namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Thumbnails;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class ThumbnailsTestCase extends CIUnitTestCase
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
	 * @var vfsStreamDirectory|null
	 */
	protected $root;

	protected function setUp(): void
	{
		parent::setUp();
		
		// Start the virtual filesystem
		$this->root = vfsStream::setup();

		// Create the service
		$this->config     = new ThumbnailsConfig();
		$this->thumbnails = new Thumbnails($this->config);
	}
	
	protected function tearDown(): void
	{
		parent::tearDown();

		$this->root = null;
	}
}
