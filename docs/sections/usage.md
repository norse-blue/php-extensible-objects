---
layout: default
title: Usage
nav_order: 2
permalink: /usage
---

# Usage
{: .no_toc }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

The primary object of this package is to offer a way to add functionality to an object through extensions that define a new behavior instead of having to directly modify the object itself or inherit from it.

There are different ways of using this package which will be outlined further down. Choose whatever suits your needs better.

## Using Inheritance

The most simple way to use the package is by extending the `ExtensibleObject` class:

```php
use NorseBlue\ExtensibleObjects\ExtensibleObject;

class MyObject extends ExtensibleObject
{
}
```

[See complete example]({{ site.baseurl }}{% link sections/examples.md %}#example-with-inheritance)

## Using composition

If you prefer composition over inheritance, you can use the provided trait and interface instead:

```php
use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

class MyObject implements Extensible
{
    use HandlesExtensionMethods;
}
```

[See complete example]({{ site.baseurl }}{% link sections/examples.md %}#example-with-composition)

## Creating an Extension Method

Once defining an extensible object we can now create an `ExtensionMethod` to add functionality to it.

We do this by implementing the `NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod` contract. This contract defines a single method `__invoke` (making the class invokable) which returns a `callable` (the actual extension function).

The extension methods can be instance or static methods.

### Instance Extension Method 

```php
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class MyExtensionMethod extends MyObject implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function(): bool {
            return $this->property;
        };
    }
}
```

Although not necessary, if your extension method is not static and you want to benefit from your IDE's auto-completion, you can extend from the extensible object class. This also serves as documenting code because it conveys the idea as to what specific object is the method extending.

[See complete example]({{ site.baseurl }}{% link sections/examples.md %}#example-of-an-instance-extension-method)

## Static Extension Methods

```php
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class MyExtensionMethod extends MyObject implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return static function(): bool {
            return self::static_property;
        };
    }
}
```

The `static` keyword in the returned `callable` will make the extensible object to handle the static call automatically.

[See complete example]({{ site.baseurl }}{% link sections/examples.md %}#example-of-a-static-extension-method)

## Extending an object

To extend an object we need to register the extension method for it. If we don't want the extension method to be available anymore we can unregister the extension method (only unguarded extension methods can be unregistered or replaced).

### Registering the Extension Method

To register the extension method for a class we need to call the `registerExtensionMethod` function on the class:

```php
MyObject::registerExtensionMethod('extension_name', MyExtensionMethod::class);
```

 If we want the extension method to be guarded (cannot be replaced or unregistered, thus is always available for the class) we need to pass `true` to the third parameter:
 
```php
MyObject::registerExtensionMethod('extension_name', MyExtensionMethod::class, true);
```  

### Unregistering the Extension Method

To unregister an extension method we can do so calling the `unregisterExtensionMethod` function with the extension method's name:

```php
MyObject::unregisterExtensionMethod('extension_name');
```

Calling this method on a guarded method will throw an `ExtensionGuardedException` exception.

## Auto-Completion for extended objects

To provide auto-completion for extension methods you have to include a _dummy_ file that can be indexed by your IDE but has no effect for anything else. MY suggestion is adding a `.ide_helper.php` file to the root of your project (outside your `src` folder if you are following the convention).

Here's an example of what a file like that could look like for the `MyObject` examples:

```php
<?php

namespace {

    exit("This file should not be included, only analyzed by your IDE.");
}

namespace NorseBlue\ExtensionObjects {

    if (false) {

        /**
         * @method bool extension_name()

         * @see \NorseBlue\ExtensibleObjects\MyExtensionMethod
         */
        class MyObject
        {
        }
    }
}

```
