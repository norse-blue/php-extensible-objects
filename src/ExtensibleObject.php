<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

class ExtensibleObject implements Extensible
{
    use HandlesExtensionMethods;
}
