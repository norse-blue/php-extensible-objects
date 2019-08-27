<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;

/**
 * Exception thrown when trying to get a nonexistent extension from the extension collection.
 */
final class ExtensionNotFoundException extends RuntimeException
{
}
