<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use NorseBlue\ExtensibleObjects\Extension;
use NorseBlue\ExtensibleObjects\Extensions\ExtensionsCollection;
use NorseBlue\ExtensibleObjects\Extensions\ExtensionsRegistry;

trait InteractsWithRegistry
{
    /** @var ExtensionsRegistry The registry of the registered extensions. */
    protected static $extension_registry;

    /**
     * Get the extension registry.
     *
     * @param bool $exclude_parent
     *
     * @return ExtensionsCollection
     */
    final private static function getClassExtensions(bool $exclude_parent = false): ExtensionsCollection
    {
        $registry = static::getExtensionRegistry();

        return $registry->get(static::class, $exclude_parent);
    }

    /**
     * Get the class extension.
     *
     * @param string $name
     *
     * @return Extension
     */
    final private static function getExtension(string $name): Extension
    {
        $extensions = static::getClassExtensions();

        return $extensions->get($name);
    }

    /**
     * Get the extension registry.
     *
     * @return ExtensionsRegistry
     */
    final private static function getExtensionRegistry(): ExtensionsRegistry
    {
        if (!static::$extension_registry instanceof ExtensionsRegistry) {
            static::$extension_registry = new ExtensionsRegistry();
        }

        return static::$extension_registry;
    }
}
