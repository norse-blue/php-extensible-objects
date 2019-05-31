<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use BadMethodCallException;
use Closure;
use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Exceptions\GuardedExtensionMethodException;
use NorseBlue\ExtensibleObjects\ExtensionMethodLoader;

/**
 * Trait HandlesExtensionMethods
 *
 * @package NorseBlue\ExtensibleObjects\Traits
 */
trait HandlesExtensionMethods
{
    /** @var array<string, callable> The registered extensions. */
    protected static $extensions = [];

    /** @protected @static @var array<string> $guarded_extensions The guarded extension methods. */

    /**
     * Register extension method.
     *
     * If given an array, all names will be registered to the given callable (like defining aliases).
     *
     * @param string|array<string> $names The name(s) of the extension method.
     * @param string|callable $extension The extension method class name or callable.
     *
     * @return void
     */
    final public static function registerExtensionMethod($names, $extension): void
    {
        $names = is_string($names) ? [$names] : $names;
        $extension = ExtensionMethodLoader::load($extension);

        foreach ($names as $name) {
            if (static::isGuardedExtensionMethod($name)) {
                throw new GuardedExtensionMethodException(
                    "The extension method '$name' is guarded and cannot be overridden."
                );
            }

            static::$extensions[static::class][$name] = $extension;
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
            if (static::isGuardedExtensionMethod($name)) {
                throw new GuardedExtensionMethodException(
                    "The extension method '$name' is guarded and cannot be unset."
                );
            }

            unset(static::$extensions[static::class][$name]);
        }
    }

    /**
     * Check if the extension method is registered.
     *
     * @param string $name The name of the extension method.
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return bool true if the extension is registered, false otherwise.
     */
    final public static function hasExtensionMethod(string $name, bool $exclude_parent = false): bool
    {
        if (isset(static::$extensions[static::class][$name])) {
            return true;
        }

        return $exclude_parent ? false : isset(static::getParentExtensionMethods()[$name]);
    }

    /**
     * Check if the extension method is guarded.
     *
     * @param string $name The name of the extension method.
     *
     * @return bool
     */
    final public static function isGuardedExtensionMethod(string $name): bool
    {
        if (!in_array($name, static::$guarded_extensions ?? []) || !static::hasExtensionMethod($name)) {
            return false;
        }

        return true;
    }

    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent If true, excludes parent extension methods.
     *
     * @return array<string, callable>
     */
    final public static function getExtensionMethods(bool $exclude_parent = false): array
    {
        $base_extensions = [];
        if (!$exclude_parent) {
            $base_extensions = static::getParentExtensionMethods();
        }

        return array_merge($base_extensions, static::$extensions[static::class]);
    }

    /**
     * Get the guarded extension methods.
     *
     * @return array<string>
     */
    final public static function getGuardedExtensionMethods(): array
    {
        return static::$guarded_extensions ?? [];
    }

    /**
     * Get the parent extension methods.
     *
     * @return array<string, callable>
     */
    final public static function getParentExtensionMethods(): array
    {
        $parent = get_parent_class(static::class);
        if ($parent === false || !is_subclass_of($parent, Extensible::class)) {
            return [];
        }

        /** @var Extensible $parent */
        return $parent::getExtensionMethods();
    }

    //region Magic Methods =====

    /**
     * Handle calls to dynamic methods.
     *
     * @param string $name The method name.
     * @param array<mixed> $parameters The method parameters.
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    final public function __call(string $name, array $parameters)
    {
        if (!static::hasExtensionMethod($name)) {
            throw new BadMethodCallException(
                sprintf('Extension method %s::%s does not exist.', static::class, $name)
            );
        }

        $callable = static::getExtensionMethods()[$name];
        $extension = Closure::fromCallable($callable())
            ->bindTo($this, static::class);

        return $extension(...$parameters);
    }

    //endregion Magic Methods
}
