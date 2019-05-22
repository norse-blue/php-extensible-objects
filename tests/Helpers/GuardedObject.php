<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

/**
 * Class GuardedObject
 *
 * @package NorseBlue\ExtensibleObjects\Tests\Helpers
 */
class GuardedObject implements Extensible
{
    use HandlesExtensionMethods;

    protected static $guarded_extensions = [
        'guarded',
        'unregisterable',
    ];
}
