<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

trait HandlesExtensionMethods
{
    use ImplementsExtensible;

    /**
     * Handle calls to extension methods.
     *
     * @param string $name The extension name.
     * @param array<mixed> $parameters The method parameters.
     */
    final public static function __callStatic(string $name, array $parameters): mixed
    {
        $extension = self::getRegistry()->get(static::class)->get($name);

        return $extension->execute(static::class, $parameters);
    }

    /**
     * Handle calls to extension methods.
     *
     * @param string $name The extension name.
     * @param array<mixed> $parameters The method parameters.
     */
    final public function __call(string $name, array $parameters): mixed
    {
        $extension = self::getRegistry()->get(static::class)->get($name);

        return $extension->execute(static::class, $parameters, $this);
    }
}
