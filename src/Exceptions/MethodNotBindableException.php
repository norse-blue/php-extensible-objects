<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;

/**
 * Exception thrown when trying to bind a Closure unsuccessfully.
 *
 * @internal
 */
final class MethodNotBindableException extends RuntimeException
{
}
