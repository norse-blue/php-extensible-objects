<?php

namespace NorseBlue\ExtensibleObjects\Contracts;

/**
 * Interface Extensible
 *
 * @package NorseBlue\ExtensibleObjects\Contracts
 */
interface Extensible
{
    /**
     * Register extension method.
     *
     * @param string $name The name of the extension method.
     * @param string|callable $extension The extension method class name or callable.
     *
     * @return void
     */
    public static function registerExtensionMethod(string $name, $extension): void;

    /**
     * Unregister extension method.
     *
     * @param string $name The name of the extension method.
     *
     * @return void
     */
    public static function unregisterExtensionMethod(string $name): void;

    /**
     * Check if the extension is registered.
     *
     * @param string $name The name of the extension method.
     *
     * @return bool True if the extension is registered, false otherwise.
     */
    public static function hasExtensionMethod(string $name): bool;

    /**
     * Get the registered extension methods.
     *
     * @return callable[]
     */
    public static function getExtensionMethods(): array;
}
