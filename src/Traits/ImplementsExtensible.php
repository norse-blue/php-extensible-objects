<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use NorseBlue\ExtensibleObjects\Guards\MethodDefinedInClassGuard;
use NorseBlue\ExtensibleObjects\Resolvers\ExtensionResolver;

trait ImplementsExtensible
{
    use InteractsWithExtensionsRegistry;

    /** @var bool Whether to guard all extensions by default or not. */
    protected static $guard_extensions = false;

    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return array<string, array<string, mixed>>
     */
    final public static function getExtensionMethods(bool $exclude_parent = false): array
    {
        return self::getRegistry()->get(static::class, $exclude_parent)->toArray();
    }

    /**
     * Check if the extension method is registered.
     *
     * @param string $name The name of the extension method.
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return bool True if the extension is registered, false otherwise.
     */
    final public static function hasExtensionMethod(string $name, bool $exclude_parent = false): bool
    {
        return self::getRegistry()->get(static::class, $exclude_parent)->has($name);
    }

    /**
     * Register extension method.
     *
     * If given an array, all names will be registered to the given callable (like defining aliases).
     *
     * @param string|array<string> $names The name(s) of the extension method.
     * @param string|callable $extension The extension method class name or callable.
     * @param bool|null $guard Whether to guard the extension method being registered or not.
     *
     * @throws \ReflectionException
     */
    final public static function registerExtensionMethod(
        $names,
        $extension,
        ?bool $guard = null
    ): void {
        $guard = $guard === null ? (bool)static::$guard_extensions : $guard;
        $extension = ExtensionResolver::resolve($extension, $guard);

        $names = is_string($names) ? [$names] : $names;
        foreach ($names as $name) {
            MethodDefinedInClassGuard::enforce(static::class, $name);
            self::getRegistry()->add(static::class, $name, $extension);
        }
    }

    /**
     * Unregister extension methods.
     *
     * @param string|array<string> $names The name(s) of the extension method.
     *
     * @return void
     */
    final public static function unregisterExtensionMethod($names): void
    {
        $names = is_string($names) ? [$names] : $names;
        foreach ($names as $name) {
            self::getRegistry()->remove(static::class, $name);
        }
    }
}
