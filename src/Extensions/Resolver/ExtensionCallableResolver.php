<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Extensions\Resolver;

use NorseBlue\ExtensibleObjects\Contracts\Creatable;
use ReflectionClass;
use ReflectionMethod;

final class ExtensionCallableResolver
{
    // @codeCoverageIgnoreStart
    private function __construct()
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * Check if the class constructor is accessible.
     *
     * @param string $class
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    protected static function isConstructorAccessible(string $class): bool
    {
        $methods = array_map(
            static function ($item) {
                return $item->name;
            },
            (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC)
        );

        return in_array('__construct', $methods);
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
        if (!self::isConstructorAccessible($extension)) {
            if (is_subclass_of($extension, Creatable::class)) {
                /** @var Creatable $extension */
                return $extension::create();
            }
        }

        return new $extension();
    }
}
