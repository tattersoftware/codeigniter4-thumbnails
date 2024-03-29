# Tatter\Thumbnails
Modular thumbnail generation, for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-thumbnails/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-thumbnails/actions/workflows/test.yml)
[![](https://github.com/tattersoftware/codeigniter4-thumbnails/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-thumbnails/actions/workflows/analyze.yml)
[![](https://github.com/tattersoftware/codeigniter4-thumbnails/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-thumbnails/actions/workflows/inspect.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-thumbnails/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-thumbnails?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/thumbnails`
2. Load the service: `$thumbnails = service('thumbnails');`
3. Create your first thumbnail: `$thumbnails->create('bigfile.jpg', 'thumbnail.jpg');`

## Features

**Thumbnails** uses CodeIgniter4's native Image class for fast and efficient
processing. It also checks for modular support files so it can grow to cover anything you
could possibly want to thumbnail: videos, 3D assets, text documents.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
```bash
composer require tatter/thumbnails
```

Or, install manually by downloading the source files and adding the directory to
**app/Config/Autoload.php**.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**examples/Thumbnails.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage

* Load the service: `$thumbnails = service('thumbnails');`
* Use the config file to define parameters, or change them on-the-fly:
```
$thumbnails->setImageType(IMAGETYPE_PNG);
$thumbnails->setWidth(120);
```
* Use the `create()` method to write the thumbnail out to a convenient location: `$thumbnails->create('bigfile.jpg', 'thumbnail.png');`

## Extending

The library looks across all namespaces for a **Thumbnailers/** directory and loads any
supported classes it find. Each class defines the extensions it supports as well as its own
`process()` method to generate the image. Add additional extension support from other modules,
or write your own. Files will be processed by matching the extension to each handler's list
of supported extension. You may also specify a specific handler to use by with the
`setHandler(string $id)` method (see [Tatter\Handlers](https://github.com/tattersoftware/codeigniter4-handlers)).

## Contributing

This library will periodically update with new supported extensions, but please feel free
to submit Pull Requests with additional handlers (or bug fixes).
