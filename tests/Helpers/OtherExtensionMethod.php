<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

/**
 * Class OtherExtensionMethod
 *
 * @package NorseBlue\ExtensibleObjects\Tests\Helpers
 */
class OtherExtensionMethod extends GuardedObject implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function (): string {
            return 'other';
        };
    }
}
