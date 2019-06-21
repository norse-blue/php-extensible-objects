<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use NorseBlue\ExtensibleObjects\Guards\MethodDefinedInClassGuard;
use NorseBlue\ExtensibleObjects\Resolvers\ExtensionResolver;

trait HandlesExtensionMethods
{
    use InteractsWithRegistry;

    /** @var bool Whether to guard all extensions by default or not. */
    protected static $guard_extensions = false;

    /**
     * Handle calls to extension methods.
     *
     * @param string $name The extension name.
     * @param array<mixed> $parameters The method parameters.
     *
     * @return mixed
     */
    final public static function __callStatic(string $name, array $parameters)
    {
        $extension = static::getExtension($name);

        return $extension->execute(static::class, $parameters);
    }

    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return array<string, array<string, mixed>>
     */
    final public static function getExtensionMethods(bool $exclude_parent = false): array
    {
        return static::getClassExtensions($exclude_parent)->toArray();
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
        return static::getClassExtensions($exclude_parent)->has($name);
    }

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
     * @throws \ReflectionException
     */
    final public static function registerExtensionMethod(
        $names,
        $extension,
        bool $static = false,
        ?bool $guard = null
    ): void {
        $guard = $guard === null ? (bool)static::$guard_extensions : $guard;
        $extension = ExtensionResolver::resolve($extension, $static, $guard);

        $names = is_string($names) ? [$names] : $names;
        foreach ($names as $name) {
            MethodDefinedInClassGuard::enforce(static::class, $name);
            static::getExtensionRegistry()->add(static::class, $name, $extension);
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
            static::getExtensionRegistry()->remove(static::class, $name);
        }
    }

    /**
     * Handle calls to extension methods.
     *
     * @param string $name The extension name.
     * @param array<mixed> $parameters The method parameters.
     *
     * @return mixed
     */
    final public function __call(string $name, array $parameters)
    {
        $extension = static::getExtension($name);

        return $extension->execute(static::class, $parameters, $this);
    }
}
