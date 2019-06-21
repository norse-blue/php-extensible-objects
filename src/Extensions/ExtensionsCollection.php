<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Extensions;

use NorseBlue\ExtensibleObjects\Exceptions\ExtensionGuardedException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotFoundException;
use NorseBlue\ExtensibleObjects\Extension;

final class ExtensionsCollection
{
    /** @var array<string, Extension> */
    protected $items;

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
     * @param string $name
     * @param Extension|array<string, mixed> $extension
     */
    public function add(string $name, $extension): void
    {
        if ($this->isGuarded($name)) {
            throw new ExtensionGuardedException("The extension '$name' is guarded, cannot replace.");
        }

        if (is_array($extension)) {
            $extension = new Extension(
                $extension['method'],
                $extension['guarded'] ?? false,
                $extension['static'] ?? false,
            );
        }

        $this->items[$name] = $extension;
    }

    /**
     * Get the extension from the collection.
     *
     * @param string $name
     *
     * @return Extension
     *
     * @throws ExtensionNotFoundException
     */
    public function get(string $name): Extension
    {
        if (!$this->has($name)) {
            throw new ExtensionNotFoundException("Cannot find the extension '$name'.");
        }

        return $this->items[$name];
    }

    /**
     * Check if the collection has the extension.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return in_array($name, array_keys($this->items));
    }

    /**
     * Check if the collection has the extension and is guarded.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isGuarded(string $name): bool
    {
        return $this->has($name) && $this->get($name)->is_guarded;
    }

    /**
     * Remove an extension from the collection.
     *
     * @param string $name
     */
    public function remove(string $name): void
    {
        if ($this->isGuarded($name)) {
            throw new ExtensionGuardedException("The extension '$name' is guarded, cannot remove.");
        }

        unset($this->items[$name]);
    }

    /**
     * Convert the collection to array.
     *
     * @return array<string, array<string, mixed>>
     */
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
