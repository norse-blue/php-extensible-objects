<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\ExtensibleObject;

/**
 * @method static string static_property_extension()
 */
class StaticPropertyObject extends ExtensibleObject
{
    protected static $property = 'my_value';
}
