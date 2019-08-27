<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;

/**
 * Exception thrown when trying to register an extension method that does not implement the ExtensionMethod contract.
 */
final class ClassNotExtensionMethodException extends RuntimeException
{
}
