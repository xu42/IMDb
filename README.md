# Imdb

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Crawl IMDb movie rating, content rating, release date, poster, presentation, director, actor, duration and other information

## Install

Via Composer

``` bash
$ composer require xu42/imdb
```

## Usage

``` php
require_once './vendor/autoload.php';
$title = 'tt0111161';
$oneTitle = new \Xu42\Imdb\OneTitle();
print_r($oneTitle->getMsgOfOneTitle($title));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Tests unavailable.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please using the issue tracker.

## Credits

- [Xu42](https://github.com/xu42)
- [All Contributors](https://github.com/xu42/IMDb/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/xu42/imdb.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/xu42/imdb.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/xu42/imdb
[link-travis]: https://travis-ci.org/xu42/imdb
[link-scrutinizer]: https://scrutinizer-ci.com/g/xu42/imdb/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/xu42/imdb
[link-downloads]: https://packagist.org/packages/xu42/imdb
[link-author]: https://github.com/xu42
[link-contributors]: ../../contributors
