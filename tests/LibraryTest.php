<?php

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use Tatter\Thumbnails\Exceptions\ThumbnailsException;
use Tatter\Thumbnails\Thumbnails;
use Tests\Support\TestCase;
use Tests\Support\Thumbnailers\MockThumbnailer;

/**
 * @internal
 */
final class LibraryTest extends TestCase
{
    public function testSetWidth()
    {
        $this->thumbnails->setWidth(42);

        $result = $this->getPrivateProperty($this->thumbnails, 'width');

        $this->assertSame(42, $result);
    }

    public function testSetHeight()
    {
        $this->thumbnails->setHeight(42);

        $result = $this->getPrivateProperty($this->thumbnails, 'height');

        $this->assertSame(42, $result);
    }

    public function testSetImageType()
    {
        $this->thumbnails->setImageType(IMAGETYPE_WBMP);

        $result = $this->getPrivateProperty($this->thumbnails, 'imageType');

        $this->assertSame(IMAGETYPE_WBMP, $result);
    }

    public function testCreatesFile()
    {
        $result = $this->thumbnails->create($this->input);

        $this->assertFileExists($result);
    }

    public function testCreateThrowsNoFile()
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('File not found: banana');

        $this->thumbnails->create('banana');
    }

    public function testCreateThrowsNoHandler()
    {
        // Disable the universal MockHandler
        config('Handlers')->ignoredClasses = [MockThumbnailer::class];

        // Use a fresh instance to trigger factory re-discovery
        $thumbnails = new Thumbnails($this->config);

        $this->expectException(ThumbnailsException::class);
        $this->expectExceptionMessage('No handler found for file extension: php');

        $thumbnails->create(__FILE__);
    }

    public function testCreateGathersErrors()
    {
        MockThumbnailer::$shouldError = true;

        try {
            $this->thumbnails->create(__FILE__);
        } catch (ThumbnailsException $e) {
        }

        $result = $this->thumbnails->getErrors();
        $this->assertCount(1, $result);
        $this->assertSame(['mock' => 'This error happened.'], $result);
    }

    public function testCreateThrowsFailure()
    {
        MockThumbnailer::$shouldError = true;

        $this->expectException(ThumbnailsException::class);
        $this->expectExceptionMessage('Thumbnail creation failed for file: ' . __FILE__);

        $this->thumbnails->create(__FILE__);
    }

    public function testCreateUsesHandler()
    {
        MockThumbnailer::$didProcess = false;

        $this->thumbnails->setHandler('mock');
        $this->thumbnails->create($this->input);

        $this->assertTrue(MockThumbnailer::$didProcess);
    }
}
