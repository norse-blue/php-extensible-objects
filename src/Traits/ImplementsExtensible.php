<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use NorseBlue\ExtensibleObjects\Guards\MethodDefinedInClassGuard;
use NorseBlue\ExtensibleObjects\Resolvers\ExtensionResolver;
use ReflectionException;

trait ImplementsExtensible
{
    use InteractsWithExtensionsRegistry;

    /** @var bool Whether to guard all extensions by default or not. */
    protected static bool $guard_extensions = false;

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
     * @throws ReflectionException
     */
    final public static function registerExtensionMethod(
        string|array $names,
        string|callable $extension,
        ?bool $guard = null
    ): void {
        $guard = $guard ?? static::$guard_extensions;
        $resolved_extension = ExtensionResolver::resolve($extension, $guard ?? false);

        $names = is_string($names) ? [$names] : $names;
        foreach ($names as $name) {
            MethodDefinedInClassGuard::enforce(static::class, $name);
            self::getRegistry()->add(static::class, $name, $resolved_extension);
        }
    }

    /**
     * Unregister extension methods.
     *
     * @param string|array<string> $names The name(s) of the extension method.
     */
    final public static function unregisterExtensionMethod(string|array $names): void
    {
        $names = is_string($names) ? [$names] : $names;
        foreach ($names as $name) {
            self::getRegistry()->remove(static::class, $name);
        }
    }
}
