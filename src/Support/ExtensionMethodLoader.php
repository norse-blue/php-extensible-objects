<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Support;

use NorseBlue\ExtensibleObjects\Contracts\Creatable;
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethodException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;
use ReflectionClass;
use ReflectionException;

abstract class ExtensionMethodLoader
{
    /**
     * Get the extension method closure.
     *
     * @param string|callable $extension
     *
     * @return string|callable
     */
    final protected static function prepareExtensionMethod($extension)
    {
        if (is_string($extension) && class_exists($extension)) {
            self::guardInvalidExtensionMethodClass($extension);

            return static::getMethodInstance($extension);
        }

        return $extension;
    }

    /**
     * Get the extension method instance.
     *
     * @param string $extension
     *
     * @return callable
     */
    final protected static function getMethodInstance(string $extension): callable
    {
        if (!static::isConstructorAccessible($extension)) {
            /** @var ExtensionMethod $extension */
            if (is_subclass_of($extension, Creatable::class)) {
                /** @var Creatable $extension */
                return $extension::create();
            }
        }

        return new $extension();
    }

    /**
     * Check if the class constructor is accessible.
     *
     * @param string $class
     *
     * @return bool
     */
    final protected static function isConstructorAccessible(string $class): bool
    {
        try {
            do {
                $reflection = new ReflectionClass($class);
                $constructor = $reflection->getConstructor();
                $class = $reflection->getParentClass();
            } while ($constructor === null && $class !== null);
        } catch (ReflectionException $exception) {
            return false;
        }

        return $constructor === null ? false : $constructor->isPublic();
    }

    /**
     * Guard from an invalid extension method class.
     *
     * @param string $extension
     *
     * @return void
     */
    final protected static function guardInvalidExtensionMethodClass(string $extension): void
    {
        if (!is_subclass_of($extension, ExtensionMethod::class)) {
            throw new ClassNotExtensionMethodException(
                sprintf(
                    "The extension method class '$extension' must implement interface %s.",
                    ExtensionMethod::class
                )
            );
        }
    }

    /**
     * Load the extension method.
     *
     * @param string|callable $extension
     *
     * @return callable
     */
    final public static function load($extension): callable
    {
        $extension = self::prepareExtensionMethod($extension);

        if (!is_callable($extension)) {
            throw new ExtensionNotCallableException("The extension method '$extension' is not callable.");
        }

        return $extension;
    }
}
