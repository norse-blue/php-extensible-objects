<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

class GuardedObject implements Extensible
{
    use HandlesExtensionMethods;

    protected static $guarded_extensions = [
        'guarded',
        'unregisterable',
    ];
}
