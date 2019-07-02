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
     * @param array|null $meta
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public static function resolve(string $class, ?array & $meta = null): bool
    {
        $reflection = new ReflectionClass($class);
        $methods = array_reduce(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            static function ($carry, $item) {
                $carry[$item->name] = $item;

                return $carry;
            },
            []
        );

        $meta = [];
        if (!in_array('__construct', array_keys($methods))) {
            return false;
        }

        $meta['params'] = $methods['__construct']->getNumberOfParameters();
        $meta['requiredParams'] = $methods['__construct']->getNumberOfRequiredParameters();

        return true;
    }
}
