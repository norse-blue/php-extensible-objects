<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Resolvers;

use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;
use NorseBlue\ExtensibleObjects\Extension;
use NorseBlue\ExtensibleObjects\Guards\InvalidExtensionGuard;

final class ExtensionResolver
{
    // @codeCoverageIgnoreStart
    private function __construct()
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * Resolve the extension method.
     */
    public static function resolve(string|callable $extension, bool $guard): Extension
    {
        if (is_string($extension) && class_exists($extension)) {
            InvalidExtensionGuard::enforce($extension);

            $extension = ExtensionCallableResolver::resolve($extension);
        }

        if (! is_callable($extension)) {
            throw new ExtensionNotCallableException("The extension method '$extension' is not callable.");
        }

        return new Extension($extension, $guard);
    }
}
