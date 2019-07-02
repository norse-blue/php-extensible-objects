<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class CreatableObjectExtensionMethod extends CreatableObject implements ExtensionMethod
{
    public function __invoke(): callable
    {
        return function (): string {
            return 'created';
        };
    }
}
