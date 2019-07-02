<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Resolvers;

use ReflectionClass;
use ReflectionMethod;

final class ClassConstructorAccessibleResolver
{
    /**
     * Check if the class constructor is accessible.
     *
     * @param string $class
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public static function resolve(string $class): bool
    {
        $methods = array_map(
            static function ($item) {
                return $item->name;
            },
            (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC)
        );

        return in_array('__construct', $methods, true);
    }
}
