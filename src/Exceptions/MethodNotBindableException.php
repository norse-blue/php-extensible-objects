<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when trying to bind a Closure unsuccessfully.
 *
 * @internal
 */
final class MethodNotBindableException extends RuntimeException
{
    protected string $errfile;
    protected int $errline;

    public function __construct(
        string $message = '',
        int $code = 0,
        string $errfile = '',
        int $errline = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );

        $this->errfile = $errfile;
        $this->errline = $errline;
    }

    public function getErrfile(): string
    {
        return $this->errfile;
    }

    public function getErrline(): int
    {
        return $this->errline;
    }
}
