# Gotipath Object storage

Gotipath Storage is a file storage library for PHP. It provides one interface to interact with FTP/SFTP. When you use
this package, you're protected from vendor lock-in, That mean you can connect to any FTP/SFTP storage. Also it's comes
with base URL option to connect Gotipath CDN.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gotipath/storage.svg?style=flat-square)](https://packagist.org/packages/gotipath/storage)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gotipath/storage/run-tests?label=tests)](https://github.com/gotipath/storage/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/gotipath/storage/Check%20&%20fix%20styling?label=code%20style)](https://github.com/gotipath/storage/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/gotipath/storage.svg?style=flat-square)](https://packagist.org/packages/gotipath/storage)

---
This package can be used as a framework-agnostic package. Follow these steps to get started:

## Installation

You can install the package via composer:

```bash
composer require gotipath/storage
```

## Usage

```php
require_once __DIR__ . '/vendor/autoload.php';

$storage = new Storage('sftp', [
    'host' => 'ftp.fas.xyx.com',
    'username' => 'sftp/ftp username',
    'password' => 'sftp/ftp password',
    //if you connecting ssh
    // 'privateKey' => '/path/to/privateKey',
//            'password' => 'encryption-password',
    'port' => 22,
    'root' => '/pub',
    'timeout' => 30,
],$baseUrl='https://cdn.gotipath.com');

```

## create directory

```php
    $path = 'uploads/testdir';
    $config = []; // optional
    $storage->makeDirectory($path, $config);
```

## get file

```php
    $storage->get($path);
```

## multipart/formdata upload

```php
    $inputFile = $_FILES['file'];

    $path = 'uploads/'.$inputFile['name'];

    $temp = $_FILES["file"]["tmp_name"];

    $storage->upload($path,$temp);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sohel Mia](https://github.com/gotipath)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
