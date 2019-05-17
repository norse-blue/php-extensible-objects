<?php

namespace NorseBlue\ExtensibleObjects\Traits;

use BadMethodCallException;
use Closure;
use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethodException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;

trait HandlesExtensionMethods
{
    /** @var callable[] The registered extensions. */
    protected static $extensions = [];

    /**
     * Register extension method.
     *
     * @param string $name The name of the extension method.
     * @param string|callable $extension The extension method class name or callable.
     *
     * @return void
     */
    public static function registerExtensionMethod(string $name, $extension): void
    {
        if (is_string($extension) && class_exists($extension)) {
            if (!is_subclass_of($extension, ExtensionMethod::class)) {
                throw new ClassNotExtensionMethodException(
                    sprintf(
                        "The extension method class '$extension' must implement interface %s.",
                        ExtensionMethod::class
                    )
                );
            }

            $extension = new $extension;
        }

        if (!is_callable($extension)) {
            throw new ExtensionNotCallableException("The extension method '$extension' is not callable.");
        }

        static::$extensions[$name] = $extension;
    }

    /**
     * Unregister extension method.
     *
     * @param string $name The name of the extension method.
     *
     * @return void
     */
    public static function unregisterExtensionMethod(string $name): void
    {
        unset(static::$extensions[$name]);
    }

    /**
     * Check if the extension is registered.
     *
     * @param string $name The name of the extension method.
     * @param bool $exclude_parent If true, excludes parent extension methods
     *
     * @return bool true if the extension is registered, false otherwise.
     */
    public static function hasExtensionMethod(string $name, bool $exclude_parent = false): bool
    {
        if (isset(static::$extensions[$name])) {
            return true;
        }

        return ($exclude_parent) ? false : isset(static::getParentExtensionMethods()[$name]);
    }

    /**
     * Get the registered extension methods.
     *
     * @param bool $exclude_parent If true, excludes parent extension methods
     *
     * @return callable[]
     */
    public static function getExtensionMethods(bool $exclude_parent = false): array
    {
        $base_extensions = [];
        if (!$exclude_parent) {
            $base_extensions = static::getParentExtensionMethods();
        }

        return array_merge($base_extensions, static::$extensions);
    }

    /**
     * Get the parent extension methods.
     *
     * @return callable[]
     */
    public static function getParentExtensionMethods(): array
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
     * @param array $parameters The method parameters.
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call(string $name, $parameters)
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
