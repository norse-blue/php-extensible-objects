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

    /** @var bool */
    protected $guarded;

    /** @var callable */
    protected $method;

    /**
     * Create a new instance.
     *
     * @param callable $method
     * @param bool $guarded
     */
    public function __construct(callable $method, bool $guarded)
    {
        $this->method = $method;
        $this->guarded = $guarded;
    }

    protected function accessorIsGuarded(): bool
    {
        return $this->guarded;
    }

    protected function accessorMethod(): callable
    {
        return $this->method;
    }

    /**
     * Determine if the closure is static.
     *
     * @param Closure $closure
     *
     * @return bool
     */
    protected function isClosureStatic(Closure $closure): bool
    {
        set_error_handler(
            static function ($errno, $errstr): void {
                if ($errstr === 'Cannot bind an instance to a static closure') {
                    throw new MethodNotBindableException($errstr, $errno);
                }
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

    /**
     * Execute the extension method.
     *
     * @param string $scope The new scope of the extension method.
     * @param array<mixed> $parameters The extension method parameters to use.
     * @param object|null $caller The caller object.
     *
     * @return mixed
     */
    public function execute(string $scope, array $parameters, ?object $caller = null)
    {
        $method = $this->method;

        $closure = Closure::fromCallable($method());
        if ($this->isClosurestatic($closure)) {
            $caller = null;
        }
        $closure = $closure->bindTo($caller, $scope);

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
            'guarded' => $this->guarded,
        ];
    }
}
