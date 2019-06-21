<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Contracts;

interface Extensible
{
    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getExtensionMethods(bool $exclude_parent = false): array;

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
     * Register extension method.
     *
     * If given an array, all names will be registered to the given callable (like defining aliases).
     *
     * @param string|array<string> $names The name(s) of the extension method.
     * @param string|callable $extension The extension method class name or callable.
     * @param bool $static Whether the extension should be called statically or not.
     * @param bool|null $guard Whether to guard the extension method being registered or not.
     *
     * @return void
     */
    public static function registerExtensionMethod($names, $extension, bool $static = false, ?bool $guard = null): void;

    /**
     * Unregister extension methods.
     *
     * @param string|array<string> $names The name(s) of the extension method.
     *
     * @return void
     */
    public static function unregisterExtensionMethod($names): void;
}
