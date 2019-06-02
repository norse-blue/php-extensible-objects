<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Support;

use NorseBlue\ExtensibleObjects\Contracts\Creatable;
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethodException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;

abstract class ExtensionMethodLoader
{
    /**
     * Load the extension method.
     *
     * @param string|callable $extension
     *
     * @return callable
     */
    public static function load($extension): callable
    {
        $extension = self::prepareExtensionMethod($extension);

        if (!is_callable($extension)) {
            throw new ExtensionNotCallableException("The extension method '$extension' is not callable.");
        }

        return $extension;
    }

    /**
     * Get the extension method closure.
     *
     * @param string|callable $extension
     *
     * @return string|callable
     */
    protected static function prepareExtensionMethod($extension)
    {
        if (is_string($extension) && class_exists($extension)) {
            self::guardInvalidExtensionMethodClass($extension);

            /** @var ExtensionMethod $extension */
            if (is_subclass_of($extension, Creatable::class)) {
                /** @var Creatable $extension */
                return $extension::create();
            }

            return new $extension();
        }

        return $extension;
    }

    /**
     * Guard from an invalid extension method class.
     *
     * @param string $extension
     *
     * @return void
     */
    protected static function guardInvalidExtensionMethodClass(string $extension): void
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
}
