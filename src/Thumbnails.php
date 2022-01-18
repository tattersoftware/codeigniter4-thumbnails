<?php

namespace Tatter\Thumbnails;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Exceptions\ThumbnailsException;
use Tatter\Thumbnails\Factories\ThumbnailerFactory;
use Tatter\Thumbnails\Interfaces\ThumbnailerInterface;
use Throwable;

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
     * Any error messages from the last run.
     *
     * @var array<string, string> Errors as [handlerId => Message]
     */
    protected $errors = [];

    /**
     * The image type constant.
     *
     * @var int
     *
     * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
     */
    protected $imageType;

    /**
     * handlerId of an explicit handler to use instead of matching.
     *
     * @var string|null
     */
    protected $handlerId;

    /**
     * The factory for handler discovery.
     *
     * @var ThumbnailerFactory
     */
    protected $factory;

    /**
     * Initializes the library with its configuration.
     */
    public function __construct(?ThumbnailsConfig $config)
    {
        $this->setConfig($config);
        $this->factory = new ThumbnailerFactory();
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

        $this->handlerId = null;

        return $this;
    }

    /**
     * Gets any errors from the last execution.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
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
     * @param string|null $handlerId The handlerId, or null to match
     *
     * @return $this
     */
    public function setHandler(?string $handlerId = null): self
    {
        $this->handlerId = $handlerId;

        return $this;
    }

    /**
     * Gets all handlers that support a certain file extension.
     *
     * @param string $extension The file extension to match
     *
     * @return class-string<ThumbnailerInterface>[]
     */
    public function matchHandlers(string $extension): array
    {
        // Check for explicit extension support
        $handlers = $this->factory->where(['extensions has' => $extension])->findAll();

        // Add any universal handlers
        $handlers = array_merge($handlers, $this->factory->where(['extensions ===' => '*'])->findAll());

        /** @var class-string<ThumbnailerInterface>[] $handlers */
        return array_unique($handlers);
    }

    //--------------------------------------------------------------------

    /**
     * Reads and verifies the file then passes to a supported handler to
     * create the thumbnail.
     *
     * @param string $input Path to the input file
     *
     * @throws FileNotFoundException
     * @throws ThumbnailsException
     *
     * @return ?string The output path, or null if no handler succeeded
     */
    public function create(string $input): ?string
    {
        // Validate the file
        $file = new File($input);
        if (! $file->isFile()) {
            throw FileNotFoundException::forFileNotFound($input);
        }

        // Get the file extension
        if (! $extension = $file->guessExtension() ?? pathinfo($input, PATHINFO_EXTENSION)) {
            throw ThumbnailsException::forNoExtension();
        }

        // Determine which handler(s) to use
        if ($this->handlerId !== null && $class = $this->factory->find($this->handlerId)) {
            $handlers = [$class];
        } elseif ([] === $handlers = $this->matchHandlers($extension)) {
            throw ThumbnailsException::forNoHandler($extension);
        }

        // Try each handler until one succeeds
        $this->errors = [];
        /** @var class-string<ThumbnailerInterface>[] $handlers */
        foreach ($handlers as $class) {
            $handler = new $class();

            try {
                $path = $handler->process($file, $this->imageType, $this->width, $this->height);
            } catch (Throwable $e) {
                $this->errors[$handler::handlerId()] = $e->getMessage();

                continue;
            }

            // Verify the output file
            if (exif_imagetype($path) === $this->imageType) {
                $this->reset();

                return $path;
            }
        }

        throw new ThumbnailsException(lang('Thumbnails.createFailed', [$input]));
    }
}
