<?php

declare(strict_types=1);

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
     * Check if the extension method is registered.
     *
     * @param string $name The name of the extension method.
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return bool True if the extension is registered, false otherwise.
     */
    public static function hasExtensionMethod(string $name, bool $exclude_parent = false): bool;

    /**
     * Check if the extension method is guarded.
     *
     * @param string $name The name of the extension method.
     *
     * @return bool
     */
    public static function isGuardedExtensionMethod(string $name): bool;

    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return array<callable>
     */
    public static function getExtensionMethods(bool $exclude_parent = false): array;

    /**
     * Get the guarded extension methods.
     *
     * @return array<string>
     */
    public static function getGuardedExtensionMethods(): array;

    /**
     * Get the parent extension methods.
     *
     * @return array<callable>
     */
    public static function getParentExtensionMethods(): array;
}
