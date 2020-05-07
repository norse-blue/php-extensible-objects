<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Guards;

use NorseBlue\ExtensibleObjects\Exceptions\MethodDefinedInClassException;
use ReflectionClass;

final class MethodDefinedInClassGuard
{
    // @codeCoverageIgnoreStart
    private function __construct()
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * Guard from already classed defined methods.
     *
     * @throws \ReflectionException
     */
    public static function enforce(string $class, string $name): void
    {
        $methods = array_map(
            static function ($item) {
                return $item->name;
            },
            (new ReflectionClass($class))->getMethods()
        );

        if (in_array($name, $methods)) {
            throw new MethodDefinedInClassException("The method '${name}' is already defined for class '${class}'.");
        }
    }
}
