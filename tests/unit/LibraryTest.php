<?php

use Tests\Support\ThumbnailsTestCase;

/**
 * @internal
 */
final class LibraryTest extends ThumbnailsTestCase
{
    /**
     * Path to the test file
     *
     * @var string
     */
    protected $input = SUPPORTPATH . 'assets/image.jpg';

    public function testCreatesFile()
    {
        $result = $this->thumbnails->create($this->input);

        $this->assertFileExists($result);
    }
}
