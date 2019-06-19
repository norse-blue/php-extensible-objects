<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Contracts;

interface ExtensionMethod
{
    /**
     * Invokable class method.
     *
     * @return callable
     */
    public function __invoke(): callable;
}
