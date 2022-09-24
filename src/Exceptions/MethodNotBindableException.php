<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

/**
 * Exception thrown when trying to bind a Closure unsuccessfully.
 *
 * @internal
 */
final class MethodNotBindableException extends RuntimeException
{
    protected string $error_file;
    protected int $error_line;

    #[Pure]
    public function __construct(
        string $message = '',
        int $code = 0,
        string $error_file = '',
        int $error_line = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );

        $this->error_file = $error_file;
        $this->error_line = $error_line;
    }

    // @codeCoverageIgnoreStart
    public function getErrorFile(): string
    {
        return $this->error_file;
    }

    public function getErrorLine(): int
    {
        return $this->error_line;
    }
    // @codeCoverageIgnoreEnd
}
