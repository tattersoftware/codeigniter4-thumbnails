<?php

namespace Tatter\Thumbnails;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\Thumbnails\Config\Thumbnails as ThumbnailsConfig;
use Tatter\Thumbnails\Exceptions\ThumbnailsException;
use Tatter\Thumbnails\Factories\ThumbnailerFactory;
use Throwable;

class Thumbnails
{
    /**
     * The configuration instance.
     */
    protected ThumbnailsConfig $config;

    /**
     * Output width.
     */
    protected int $width;

    /**
     * Output height.
     */
    protected int $height;

    /**
     * Any error messages from the last run.
     *
     * @var array<string, string> Errors as [handler ID => Message]
     */
    protected array $errors = [];

    /**
     * The image type constant.
     *
     * @see https://www.php.net/manual/en/function.image-type-to-mime-type.php
     */
    protected int $imageType;

    /**
     * ID of an explicit thumbnailer to use instead of matching.
     */
    protected ?string $handlerId = null;

    /**
     * Initializes the library with its configuration.
     */
    public function __construct(ThumbnailsConfig $config)
    {
        $this->setConfig($config);
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

    /**
     * Specifies the handler to use instead of matching it automatically.
     *
     * @param string|null $id The thumbnailer ID, or null to match by extension
     *
     * @return $this
     */
    public function setHandler(?string $id = null): self
    {
        $this->handlerId = $id;

        return $this;
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
        $extension = pathinfo($input, PATHINFO_EXTENSION) ?: $file->guessExtension();
        if (empty($extension)) {
            throw ThumbnailsException::forNoExtension();
        }

        // Determine which handler(s) to use
        if ($this->handlerId !== null && $class = ThumbnailerFactory::find($this->handlerId)) {
            $thumbnailers = [$class];
        } elseif ([] === $thumbnailers = ThumbnailerFactory::findForExtension($extension)) {
            throw ThumbnailsException::forNoHandler($extension);
        }

        // Try each handler until one succeeds
        $this->errors = [];

        foreach ($thumbnailers as $thumbnailer) {
            try {
                $path = $thumbnailer::process($file, $this->imageType, $this->width, $this->height);
            } catch (Throwable $e) {
                $this->errors[$thumbnailer::HANDLER_ID] = $e->getMessage();

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
