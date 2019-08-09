# Tatter\Thumbnails
Modular thumbnail generation, for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/thumbnails`
2. Update the database: `> php spark migrate:latest -all`
3. Register all handlers: `> php spark handlers:register`
4. Load the service: `$thumbnails = service('thumbnails');`
5. Create your first thumbnail: `$thumbnails->create('bigfile.jpg', 'thumbnail.jpg');`

## Features

The Thumbnails library uses CodeIgniter4's native Image class for fast and efficient
processing. It also checks for modular support files so it can grow to cover anything you
could possibly want to thumbnail: videos, 3D assets, text documents.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/thumbnails`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

Once the files are downloaded and included in the autoload, run any library migrations
to ensure the database is setup correctly:
* `> php spark migrate:latest -all`

**Pro Tip:** You can add the spark command to your composer.json to ensure your database is
always current with the latest release:
```
{
	...
    "scripts": {
        "post-update-cmd": [
            "composer dump-autoload",
            "php spark migrate:latest -all"
        ]
    },
	...
```

You will also need to register handlers for the library (see Tatter\Handlers):
* `> php spark handlers:register`

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**bin/Thumbnails.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

* Load the service: `$thumbnails = service('thumbnails');`
* Use the config file to define parameters, or change them on-the-fly:
```
$thumbnails->setImageType(IMAGETYPE_PNG);
$thumbnails->width(120);
```
* Use `create($input, $output)` to write the thumbnail out to a convenient location: `$thumbnails->create('bigfile.jpg', 'thumbnail.png');`

## Extending

The library looks across all namespaces for a **Thumbnails/** directory and loads any
supported classes it find. Each class defines the extensions it supports as well as its own
`create()` method to generate the image. Add additional extension support from other modules,
or write your own.

*Note*: This library will periodically update with new extensions, but we're also always
glad for Pull Requests with additional extensions you might have.
