<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\CreatableObjects\Contracts\Creatable;
use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

/**
 * @method string creatable()
 */
class CreatableObject implements Creatable, Extensible
{
    use HandlesExtensionMethods;

    protected function __construct()
    {
    }

    public static function create()
    {
        return new static();
    }
}
