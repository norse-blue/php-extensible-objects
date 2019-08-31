<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Resolvers;

use NorseBlue\CreatableObjects\Contracts\Creatable;

final class ExtensionCallableResolver
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    /**
     * Get the extension method callable.
     *
     * @param string $extension
     *
     * @return callable
     *
     * @throws \ReflectionException
     */
    public static function resolve(string $extension): callable
    {
        if (!ClassConstructorAccessibleResolver::resolve($extension)
            && is_subclass_of($extension, Creatable::class)
        ) {
            /** @var Creatable $extension */
            return $extension::create();
        }

        return new $extension();
    }
}
