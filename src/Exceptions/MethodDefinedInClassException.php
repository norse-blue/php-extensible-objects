<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;

/**
 * Exception thrown when trying to register an extension method with the same name as a defined class method.
 */
final class MethodDefinedInClassException extends RuntimeException
{
}
