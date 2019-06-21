<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class GuardedExtensionMethod implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function (): string {
            return 'guarded';
        };
    }
}
