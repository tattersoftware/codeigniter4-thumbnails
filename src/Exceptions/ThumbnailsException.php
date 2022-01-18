<?php

namespace Tatter\Thumbnails\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use RuntimeException;

class ThumbnailsException extends RuntimeException implements ExceptionInterface
{
    public static function forNoExtension()
    {
        return new static(lang('Thumbnails.noExtension')); // @phpstan-ignore-line
    }

    public static function forNoHandler($extension)
    {
        return new static(lang('Thumbnails.noHandler', [$extension])); // @phpstan-ignore-line
    }
}
