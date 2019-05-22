<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

/**
 * Class GuardedExtensionMethod
 *
 * @package NorseBlue\ExtensibleObjects\Tests\Helpers
 */
class GuardedExtensionMethod extends GuardedObject implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function (): string {
            return 'guarded';
        };
    }
}
