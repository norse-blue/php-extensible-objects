<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Traits;

use NorseBlue\ExtensibleObjects\Extensions\ExtensionsRegistry;

trait InteractsWithExtensionsRegistry
{
    /** @var ExtensionsRegistry|null The extensions registry. */
    private static ?ExtensionsRegistry $extensions = null;

    /**
     * Get the extension registry.
     */
    final private static function getRegistry(): ExtensionsRegistry
    {
        if (!self::$extensions instanceof ExtensionsRegistry) {
            self::$extensions = new ExtensionsRegistry();
        }

        return self::$extensions;
    }
}
