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
     * @param int|null $requiredParams
     * @param int|null $params
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public static function resolve(string $class, ?int &$requiredParams = 0, ?int &$params = 0): bool
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

        if (!in_array('__construct', array_keys($methods))) {
            return false;
        }

        $requiredParams = $methods['__construct']->getNumberOfRequiredParameters();
        $params = $methods['__construct']->getNumberOfParameters();

        return true;
    }
}
