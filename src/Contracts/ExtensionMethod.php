<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Contracts;

interface ExtensionMethod
{
    /**
     * Invokable class method.
     */
    public function __invoke(): callable;
}
