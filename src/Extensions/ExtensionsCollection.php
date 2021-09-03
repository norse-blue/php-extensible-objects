<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Extensions;

use JetBrains\PhpStorm\Pure;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionGuardedException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotFoundException;
use NorseBlue\ExtensibleObjects\Extension;

/**
 * @internal
 */
final class ExtensionsCollection
{
    /** @var array<string, Extension> */
    private array $items;

    /**
     * Create a new instance.
     *
     * @param iterable<string, Extension|array<string, mixed>> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = [];
        foreach ($items as $name => $extension) {
            $this->add($name, $extension);
        }
    }

    /**
     * Add the extension to the collection.
     *
     * @param Extension|array<string, mixed> $extension
     */
    public function add(string $name, Extension|array $extension): void
    {
        if ($this->isGuarded($name)) {
            throw new ExtensionGuardedException("The extension '${name}' is guarded, cannot replace.");
        }

        if (is_array($extension)) {
            $extension = new Extension(
                $extension['method'],
                $extension['guarded'] ?? false,
            );
        }

        $this->items[$name] = $extension;
    }

    /**
     * Get the extension from the collection.
     *
     * @throws ExtensionNotFoundException
     */
    public function get(string $name): Extension
    {
        if (! $this->has($name)) {
            throw new ExtensionNotFoundException("Cannot find the extension '${name}'.");
        }

        return $this->items[$name];
    }

    /**
     * Check if the collection has the extension.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->items);
    }

    /**
     * Check if the collection has the extension and is guarded.
     */
    public function isGuarded(string $name): bool
    {
        return $this->has($name) && $this->get($name)->is_guarded;
    }

    /**
     * Merge the given extensions collection.
     */
    public function merge(?ExtensionsCollection $extensions): ExtensionsCollection
    {
        $items = $this->toArray();
        if ($extensions !== null) {
            $items = array_merge($extensions->toArray(), $items);
        }

        return new self($items);
    }

    /**
     * Remove an extension from the collection.
     */
    public function remove(string $name): void
    {
        if ($this->isGuarded($name)) {
            throw new ExtensionGuardedException("The extension '${name}' is guarded, cannot remove.");
        }

        unset($this->items[$name]);
    }

    /**
     * Convert the collection to array.
     *
     * @return array<string, array<string, mixed>>
     */
    #[Pure]
    public function toArray(): array
    {
        $arr = [];
        foreach ($this->items as $name => $extension) {
            /** @var Extension $extension */
            $arr[$name] = $extension->toArray();
        }

        return $arr;
    }
}
