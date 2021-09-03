<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Guards;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethodException;

/**
 * @internal
 */
final class InvalidExtensionGuard
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    /**
     * Guard against an invalid extension method class.
     *
     * @param string $extension The extension method class name.
     */
    public static function enforce(string $extension): void
    {
        if (! is_subclass_of($extension, ExtensionMethod::class)) {
            throw new ClassNotExtensionMethodException(
                sprintf(
                    "The extension method class '${extension}' must implement interface %s.",
                    ExtensionMethod::class
                )
            );
        }
    }
}
