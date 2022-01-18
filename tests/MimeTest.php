<?php

use Config\Mimes;
use Tatter\Thumbnails\Exceptions\ThumbnailsException;
use Tests\Support\TestCase;

/**
 * @internal
 */
final class MimeTest extends TestCase
{
    private $mimeBackup;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure no MIME type matches
        $this->mimeBackup = Mimes::$mimes;
        Mimes::$mimes     = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Restore MIME types
        Mimes::$mimes = $this->mimeBackup;
    }

    public function testCreateThrowsNoExtension()
    {
        $this->expectException(ThumbnailsException::class);
        $this->expectExceptionMessage('Unable to determine file extension for thumbnail handling');

        $this->thumbnails->create(SUPPORTPATH . 'assets/noext');
    }
}
