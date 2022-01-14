<?php

namespace Tests\Support\Thumbnails;

use CodeIgniter\Files\File;
use Tatter\Handlers\BaseHandler;
use Tatter\Thumbnails\Interfaces\ThumbnailInterface;

class MockThumbnail extends BaseHandler implements ThumbnailInterface
{
    /**
     * Attributes for Tatter\Handlers
     *
     * @var array<string, string> Must include keys: name, extensions
     */
    public $attributes = [
        'name'       => 'Mocker',
        'extensions' => '*',
    ];

    /**
     * Blindly creates a file at $output to match $imageType.
     *
     * @param File   $file      The file that needs a thumbnail
     * @param string $output    Path to the output file
     * @param int    $imageType A PHP imagetype constant, https://www.php.net/manual/en/function.image-type-to-mime-type.php
     * @param int    $width     Width of the created thumbnail
     * @param int    $height    Height of the created thumbnail
     *
     * @return bool Success or failure
     */
    public function create(File $file, string $output, int $imageType, int $width, int $height): bool
    {
        switch ($imageType) {
            case IMAGETYPE_JPEG: $path = 'jpg'; break;

            case IMAGETYPE_PNG: $path = 'png'; break;

            default: $path = '';
        }
        $path = SUPPORTPATH . 'assets/thumbnail.' . $path;

        return copy($path, $output);
    }
}
