<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;

/**
 * Exception thrown when trying to unregister or replace a guarded extension method.
 */
final class ExtensionGuardedException extends RuntimeException
{
}
