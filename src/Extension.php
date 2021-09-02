<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects;

use Closure;
use NorseBlue\ExtensibleObjects\Exceptions\MethodNotBindableException;
use NorseBlue\HandyProperties\Traits\HasPropertyAccessors;
use stdClass;

/**
 * @property-read bool $is_guarded
 * @property-read bool $is_static
 * @property-read callable $method
 */
final class Extension
{
    use HasPropertyAccessors;

    protected bool $guarded;

    /** @var callable */
    protected $method;

    protected bool $static;

    /**
     * Create a new instance.
     */
    public function __construct(callable $method, bool $guarded)
    {
        $this->method = $method;
        $this->guarded = $guarded;

        $closure = $method instanceof Closure ? $method : Closure::fromCallable($method());
        $this->static = $this->isClosureStatic($closure);
    }

    /**
     * Execute the extension method.
     *
     * @param string $scope The new scope of the extension method.
     * @param array<mixed> $parameters The extension method parameters to use.
     * @param object|null $caller The caller object.
     */
    public function execute(string $scope, array $parameters, ?object $caller = null): mixed
    {
        $method = $this->method;

        if ($this->is_static) {
            $caller = null;
        }

        $closure = Closure::fromCallable($method())
            ->bindTo($caller, $scope);

        return $closure(...$parameters);
    }

    /**
     * Get the extension as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'static' => $this->static,
            'guarded' => $this->guarded,
        ];
    }

    /**
     * Determine if the closure is static.
     */
    protected function isClosureStatic(Closure $closure): bool
    {
        set_error_handler(
            static function (int $errno, string $errstr, string $errfile, int $errline): bool {
                if ($errstr === 'Cannot bind an instance to a static closure') {
                    throw new MethodNotBindableException($errstr, $errno, $errfile, $errline);
                }

                return true;
            }
        );

        try {
            $closure->bindTo(new stdClass());
        } catch (MethodNotBindableException $exception) {
            return true;
        } finally {
            restore_error_handler();
        }

        return false;
    }

    protected function accessorIsGuarded(): bool
    {
        return $this->guarded;
    }

    protected function accessorIsStatic(): bool
    {
        return $this->static;
    }

    protected function accessorMethod(): callable
    {
        return $this->method;
    }
}
