PageSpeed Insights API
======================

A PHP module to interact with the [PageSpeed Insights API](https://developers.google.com/speed/docs/insights/v1/getting_started).

Installation
============

The best way to install the library is by using [Composer](http://getcomposer.org). Add the following to `composer.json` in the root of your project:

``` javascript
{
    "require": {
        "sgrodzicki/PageSpeed": "1.0.*"
    }
}
```

Then, on the command line:

``` bash
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Use the generated `vendor/.composer/autoload.php` file to autoload the library classes.

Basic usage
===================

```php
<?php

$pageSpeed = new \PageSpeed\Insights\Service('YOUR_API_KEY');
$pageSpeed->getResults('http://www.example.com');
```

Tests
=====

[![Build Status](https://secure.travis-ci.org/sgrodzicki/pagespeed.png?branch=master)](http://travis-ci.org/sgrodzicki/pagespeed)

The client is tested with phpunit; you can run the tests, from the repository's root, by doing:

``` bash
phpunit
```

Some tests require internet connection (to test against a real API response), so they are disabled by default; to run them add a `credentials.php` file in the root of your project:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$key = 'YOUR_API_KEY';

```

and run the tests, from the repository's root, by doing:

``` bash
phpunit --bootstrap credentials.php
```