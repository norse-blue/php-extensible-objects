<?php

namespace NorseBlue\ExtensibleObjects\Traits;

use BadMethodCallException;
use Closure;
use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethod;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;

trait HandlesExtensionMethods
{
    /**
     * @var callable[] The registered extensions.
     */
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
                throw new ClassNotExtensionMethod(
                    sprintf('The extension method class must implement interface %s.', ExtensionMethod::class)
                );
            }

            $extension = new $extension;
        }

        if (!is_callable($extension)) {
            throw new ExtensionNotCallableException('The extension method is not callable.');
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
     *
     * @return bool True if the extension is registered, false otherwise.
     */
    public static function hasExtensionMethod(string $name): bool
    {
        return isset(static::$extensions[$name]);
    }

    /**
     * Get the registered extension methods.
     *
     * @return callable[]
     */
    public static function getExtensionMethods(): array
    {
        return static::$extensions;
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
            throw new BadMethodCallException(sprintf('Extension method %s::%s does not exist.', static::class, $name));
        }

        $callable = static::$extensions[$name];
        $extension = Closure::fromCallable($callable(...$parameters))
            ->bindTo($this, static::class);

        return $extension(...$parameters);
    }

    //endregion Magic Methods
}
