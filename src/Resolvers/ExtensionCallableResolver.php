<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Resolvers;

use NorseBlue\CreatableObjects\Contracts\Creatable;

/**
 * @internal
 */
final class ExtensionCallableResolver
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    /**
     * Get the extension method callable.
     */
    public static function resolve(string $extension): callable
    {
        if (is_subclass_of($extension, Creatable::class)) {
            return $extension::create();
        }

        return new $extension();
    }
}
