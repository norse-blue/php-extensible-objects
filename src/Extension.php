<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects;

use Closure;
use NorseBlue\HandyProperties\Traits\HasPropertyAccessors;

/**
 * @property-read bool $is_guarded
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
     * Execute the extension method.
     *
     * @param object $caller The caller object.
     * @param string $scope The new scope of the extension method.
     * @param array<mixed> $parameters The extension method parameters to use.
     *
     * @return mixed
     */
    public function execute(object $caller, string $scope, array $parameters)
    {
        $method = $this->method;
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
            'guarded' => $this->guarded,
        ];
    }
}