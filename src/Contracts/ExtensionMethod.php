<?php

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
