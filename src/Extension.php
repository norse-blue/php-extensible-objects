<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects;

use Closure;
use NorseBlue\HandyProperties\Traits\HasPropertyAccessors;

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

    /** @var bool */
    protected $static;

    /**
     * Create a new instance.
     *
     * @param callable $method
     * @param bool $static
     * @param bool $guarded
     */
    public function __construct(callable $method, bool $static, bool $guarded)
    {
        $this->method = $method;
        $this->static = $static;
        $this->guarded = $guarded;
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

        if ($this->static) {
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
}
