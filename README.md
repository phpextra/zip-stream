#ZIP Stream

Stream your files as a zip stream without wasting server's memory.

[![Latest Stable Version](https://poser.pugx.org/phpextra/zip-stream/v/stable)](https://packagist.org/packages/phpextra/zip-stream) 
[![Total Downloads](https://poser.pugx.org/phpextra/zip-stream/downloads)](https://packagist.org/packages/phpextra/zip-stream) 
[![Latest Unstable Version](https://poser.pugx.org/phpextra/zip-stream/v/unstable)](https://packagist.org/packages/phpextra/zip-stream) 
[![License](https://poser.pugx.org/phpextra/zip-stream/license)](https://packagist.org/packages/phpextra/zip-stream)
[![Build Status](http://img.shields.io/travis/phpextra/zip-stream.svg)](https://travis-ci.org/phpextra/zip-stream)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpextra/zip-stream/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpextra/zip-stream/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phpextra/zip-stream/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phpextra/zip-stream/?branch=master)
[![GitTip](http://img.shields.io/gittip/jkobus.svg)](https://www.gittip.com/jkobus)

##Usage

```php
<?php

header('Content-disposition: attachment; filename="zip-stream-example.zip"');
header('Content-Type: application/octet-stream');

require_once __DIR__ . '/vendor/autoload.php';

$zipStream = new \PHPExtra\ZipStream\ZipStream(array(__DIR__ . '/5GB-large-file.mp3'));

while(!$zipStream->eof()){
    echo $zipStream->read(8192);
}

$zipStream->close();
```

## Installation (Composer)

```json
{
    "require": {
        "phpextra/zip-stream":"*"
    }
}
```

##Changelog

    No releases yet

##Contributing

All code contributions must go through a pull request.
Fork the project, create a feature branch, and send me a pull request.
To ensure a consistent code base, you should make sure the code follows
the [coding standards](http://symfony.com/doc/2.0/contributing/code/standards.html).
If you would like to help take a look at the **list of issues**.

##Authors

    Jacek Kobus - <kobus.jacek@gmail.com>

## License information

    See the file LICENSE.txt for copying permission