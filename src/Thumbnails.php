<?php

namespace Tatter\Thumbnails;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\Handlers\Handlers;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Exceptions\ThumbnailsException;
use Tatter\Thumbnails\Interfaces\ThumbnailInterface;

class Thumbnails
{
    /**
     * The configuration instance.
     *
     * @var ThumbnailsConfig
     */
    protected $config;

    /**
     * Output width.
     *
     * @var int
     */
    protected $width;

    /**
     * Output height.
     *
     * @var int
     */
    protected $height;

    /**
     * The image type constant.
     *
     * @var int
     *
     * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
     */
    protected $imageType;

    /**
     * Overriding name of a handler to use.
     *
     * @var string|null
     */
    protected $handler;

    /**
     * The library for handler discovery.
     *
     * @var Handlers
     */
    protected $handlers;

    /**
     * Initializes the library with its configuration.
     */
    public function __construct(?ThumbnailsConfig $config = null)
    {
        $this->setConfig($config);
        $this->handlers = new Handlers('Thumbnails');
    }

    /**
     * Resets library state to the provided configuration.
     * Called between each create()
     *
     * @return $this
     */
    public function reset(): self
    {
        foreach (['width', 'height', 'imageType'] as $key) {
            $this->{$key} = $this->config->{$key};
        }

        $this->handler = null;

        return $this;
    }

    /**
     * Sets the configuration to use.
     *
     * @return $this
     */
    public function setConfig(?ThumbnailsConfig $config = null): self
    {
        $this->config = $config ?? config('Thumbnails');
        $this->reset();

        return $this;
    }

    /**
     * Sets the output image type.
     *
     * @return $this
     */
    public function setImageType(int $imageType): self
    {
        $this->imageType = $imageType;

        return $this;
    }

    /**
     * Sets the output image width.
     *
     * @return $this
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Sets the output image height.
     *
     * @return $this
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Specifies the handler to use instead of matching it automatically.
     *
     * @param string|ThumbnailInterface|null $handler
     *
     * @return $this
     */
    public function setHandler($handler = null): self
    {
        if (is_string($handler) && $class = $this->handlers->find($handler)) {
            $handler = new $class();
        }
        $this->handler = $handler;

        return $this;
    }

    /**
     * Gets all handlers that support a certain file extension.
     *
     * @param string $extension The file extension to match
     *
     * @return ThumbnailInterface[]
     */
    public function matchHandlers(string $extension): array
    {
        $handlers = [];

        // Check all handlers so we can parse the extensions attribute properly
        foreach ($this->handlers->findAll() as $class) {
            $instance = new $class();

            if ($instance->extensions === '*') {
                $handlers[] = $instance;
            } elseif (stripos($instance->extensions, $extension) !== false) {
                // Make sure actual matches get preference over generic ones
                array_unshift($handlers, $instance);
            }
        }

        return $handlers;
    }

    //--------------------------------------------------------------------

    /**
     * Reads and verifies the file then passes to a supported handler to
     * create the thumbnail.
     *
     * @param string $input  Path to the input file
     * @param string $output Path to the output file
     *
     * @throws FileNotFoundException
     * @throws ThumbnailsException
     *
     * @return $this
     */
    public function create(string $input, string $output): self
    {
        // Validate the file
        $file = new File($input);
        if (! $file->isFile()) {
            throw FileNotFoundException::forFileNotFound($input);
        }

        // Get the file extension
        if (! $extension = $file->guessExtension() ?? pathinfo($input, PATHINFO_EXTENSION)) {
            throw new ThumbnailsException(lang('Thumbnails.noExtension'));
        }

        // Determine which handlers to use
        $handlers = $this->handler ? [$this->handler] : $this->matchHandlers($extension);

        // No handlers matched?
        if (empty($handlers)) {
            throw new ThumbnailsException(lang('Thumbnails.noHandler', [$extension]));
        }

        // Try each handler until one succeeds
        $result = false;

        foreach ($handlers as $handler) {
            if ($handler->create($file, $output, $this->imageType, $this->width, $this->height)) {
                // Verify the output file
                if (exif_imagetype($output) === $this->imageType) {
                    $result = true;
                    break;
                }
            }
        }

        $this->reset();

        if (! $result) {
            throw new ThumbnailsException(lang('Thumbnails.createFailed', [$input]));
        }

        return $this;
    }
}
