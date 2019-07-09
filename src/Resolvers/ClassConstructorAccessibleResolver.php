<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Resolvers;

use ReflectionClass;
use ReflectionMethod;

final class ClassConstructorAccessibleResolver
{
    /**
     * Gets the class methods.
     *
     * @param string $class
     *
     * @return array<string, ReflectionMethod>
     *
     * @throws \ReflectionException
     */
    protected static function getClassMethods(string $class): array
    {
        return array_reduce(
            (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC),
            static function ($carry, $item) {
                $carry[$item->name] = $item;

                return $carry;
            },
            []
        );
    }

    /**
     * Check if the class constructor is accessible.
     *
     * @param string $class
     * @param array<string, mixed>|null $meta
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public static function resolve(string $class, ?array & $meta = null): bool
    {
        $meta = [];
        $methods = self::getClassMethods($class);

        if (!in_array('__construct', array_keys($methods))) {
            return false;
        }

        $meta['params'] = $methods['__construct']->getNumberOfParameters();
        $meta['requiredParams'] = $methods['__construct']->getNumberOfRequiredParameters();

        return true;
    }
}
