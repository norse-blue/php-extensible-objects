---
layout: default
title: Examples
nav_order: 3
permalink: /examples
---

# Examples
{: .no_toc }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

The following examples are equivalent, each having its caveats.

## Example with inheritance

```php
<?php

declare(strict_type=1);

namespace NorseBlue\ExtensibleObjects\Examples;

use NorseBlue\ExtensibleObjects\ExtensibleObject;

class Path extends ExtensibleObject
{
    private $path;
    
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function __toString()
    {
        return $this->path;
    }
}

```

## Example with composition

```php
<?php

declare(strict_type=1);

namespace NorseBlue\ExtensibleObjects\Examples;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

class Path implements Extensible
{
    use HandlesExtensionMethods;

    private $path;
    
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function __toString()
    {
        return $this->path;
    }
}

```

## Example of an instance extension method

```php
<?php

declare(strict_type=1);

namespace NorseBlue\ExtensibleObjects\Examples;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class AppendExtension extends Path implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function(string $append): string {
            return $this->path . $append;
        };
    }
}
```

### Instance extension method registration

```php
<?php

namespace NorseBlue\ExtensibleObjects\Examples;

Path::registerExtensionMethod('append', AppendExtension::class);
```

### Instance extension method usage

```php
<?php

namespace NorseBlue\ExtensibleObjects\Examples;

$path = new Path('/home');

echo $path->append('/norse-blue');

// Outputs: /home/norse-blue
```

## Example of a static extension method

```php
<?php

declare(strict_type=1);

namespace NorseBlue\ExtensibleObjects\Examples;

class BuildExtension extends Path implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return static function(array $segments): Path {
            return new Path(DIRECTORY_SEPARATOR, $segments);
        };
    }
}
```

### Static extension method registration

```php
<?php

namespace NorseBlue\ExtensibleObjects\Examples;

Path::registerExtensionMethod('build', BuildExtension::class);
```

### Static extension method usage

```php
<?php

namespace NorseBlue\ExtensibleObjects\Examples;

$path = Path::build(['', 'home', 'norse-blue']);

echo $path;

// Outputs: /home/norse-blue
```
