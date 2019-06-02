---
layout: default
title: Home
nav_order: 1
description: PHP Extensible Objects is a PHP library that provides the mechanisms to dynamically add extension methods to objects.
---

# PHP Extensible Objects

<div align="left">
  <p align="left"> 
    <a href="https://packagist.org/packages/norse-blue/extensible-objects"><img alt="Stable Release" src="https://img.shields.io/packagist/v/norse-blue/extensible-objects.svg?color=%235e81ac&style=popout-square"></a>
    <a href="https://circleci.com/gh/norse-blue/php-extensible-objects/tree/master"><img alt="Build Status" src="https://img.shields.io/circleci/project/github/norse-blue/php-extensible-objects/master.svg?color=%23a3be8c&style=popout-square"></a>
    <a href="https://php.net/releases"><img alt="PHP Version" src="https://img.shields.io/packagist/php-v/norse-blue/extensible-objects.svg?color=%23b48ead&style=popout-square"></a>
    <a href="https://codeclimate.com/github/norse-blue/php-extensible-objects/maintainability"><img src="https://api.codeclimate.com/v1/badges/253dcf3f7fd57dab4150/maintainability" /></a>
    <a href="https://codeclimate.com/github/norse-blue/php-extensible-objects/test_coverage"><img src="https://api.codeclimate.com/v1/badges/253dcf3f7fd57dab4150/test_coverage" /></a>
    <a href="https://packagist.org/packages/norse-blue/extensible-objects"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/norse-blue/extensible-objects.svg?color=%235e81ac&style=popout-square"></a>
    <a href="https://packagist.org/packages/norse-blue/extensible-objects"><img alt="GitHub" src="https://img.shields.io/github/license/norse-blue/php-extensible-objects.svg?color=%235e81ac&style=popout-square"></a>
  </p>
</div>
<hr>

**PHP Extensible Objects** is a PHP library that provides the mechanisms to dynamically add extension methods to objects.

[Get started now](#getting-started){: .btn .btn-primary .fs-5 .mb-4 .mb-md-0 .mr-2 } [View it on GitHub](https://github.com/norse-blue/php-extensible-objects){: .btn .fs-5 .mb-4 .mb-md-0 }

---

## Getting started

### Installation

>Requirements:
>- [PHP 7.3+](https://php.net/releases)

Install Extensible Objects using Composer:

```bash
composer require norse-blue/extensible-objects
```

### Basic usage

Create a base class that is to be extensible. To be extensible it has to implement 
`NorseBlue\ExtensibleObjects\Contracts\Extensible` and it should use
`NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods`:

```php
namespace App;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

class MyObject implements Extensible {
    use HandlesExtensionMethods;
}
```

That's all there is to it. Now you can add extension methods to the class. The best way to do so is to create an
extension method class. This class must implement `NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod` interface.

_**Note:** params passed by reference in extensions are not supported because the calls depend on `__call`,
which does not pass params by reference. See [Overloading][php_overloading_url]._

```php
namespace App\Extensions;

class MyObjectExtension implements ExtensionMethod {
    public function __invoke() {
        return function($param1, $param2, $param3) {
            $str = '$param1: ' . $param1 . PHP_EOL . '$param2: ' .$param2 . PHP_EOL . '$param3: ';
            
            if (is_array($param3)) {
                foreach($param3 as $item) {
                    $str .= $item . ', ';
                }
            } else {
              $str .= 'Not an array';
            }
                        
            return rtrim($str, ', ');
        }
    }
}
```

Now you have to register the extension method anywhere in your code (obviously before using it).

To register the method you need to call the base class `registerExtensionMethod` function and pass it a name with
what you will call the extension and the callable (in this case the invokable class name):

```php
use App\MyObject;
use App\Extensions\MyObjectExtension;

MyObject::registerExtensionMethod('my_method', MyObjectExtension::class);
```

Now you can use your method as it were part of the class:

```php
$obj = new MyObject;
echo $obj->my_method('My string', 3, ['foo', 'bar', 'baz']);
```

The above code will output:

```
$param1: My string
$param2: 3
$param3: foo, bar, baz
```

The `registerExtensionMethod` accepts an array of names, so that multiple names are registered to the same extension
method (kind of like aliases, but they are registered independently, so they are not tied to one another in any way).

The extension method will be run in the class context, as it were declared inside the class. Being so, you have access
to everything inside the base class (even private properties and methods). The extension method does not have to inherit
from the base class, but doing so will help your IDE with auto completion and static analysis if using some class
methods and properties.

To unregister the extension method you can do:
```php
MyObject::unregisterExtensionMethod('my_method');
```

The `unregisterExtensionMethod` also accepts an array of names to unregister multiple extension methods in batch.

## Changelog

Please refer to the [CHANGELOG.md](https://github.com/norse-blue/php-extensible-objects/blob/master/CHANGELOG.md) file for more information about what has changed recently.

## Contributing

Contributions to this project are accepted and encouraged. Please read the [CONTRIBUTING.md](https://github.com/norse-blue/php-extensible-objects/blob/master/.github/CONTRIBUTING.md) file for details on contributions.

## Credits

- [Axel Pardemann](https://github.com/axelitus)
- [All Contributors](https://github.com/norse-blue/php-extensible-objects/graphs/contributors)

## Security

If you discover any security related issues, please email [security@norse.blue](mailto:security@norse.blue) instead of using the issue tracker.

## Support the development

**Do you like this project? Support it by donating**

<a href="https://www.buymeacoffee.com/axelitus"><img src="assets/images/buy-me-a-coffee.svg" width="180" alt="Buy me a coffee" /></a>

## License

Extensible Objects is open-sourced software licensed under the [MIT](https://github.com/norse-blue/php-extensible-objects/blob/master/LICENSE.md) license.

[php_overloading_url]: https://www.php.net/manual/en/language.oop5.overloading.php
