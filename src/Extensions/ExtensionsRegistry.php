<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Extensions;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionHasNoParentException;
use NorseBlue\ExtensibleObjects\Extension;

final class ExtensionsRegistry
{
    /** @var array<string, ExtensionsCollection> */
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Add a class extension to the registry.
     */
    public function add(string $class, string $name, Extension $extension): void
    {
        $this->getClassExtensions($class)->add($name, $extension);
    }

    /**
     * Get the class extensions collection from the registry.
     */
    public function get(string $class, bool $exclude_parent = false): ExtensionsCollection
    {
        $extensions = $this->getClassExtensions($class);

        if (! $exclude_parent) {
            $extensions = $extensions->merge($this->getParentExtensions($class));
        }

        return new ExtensionsCollection($extensions->toArray());
    }

    /**
     * Remove the class extension from the registry.
     */
    public function remove(string $class, string $name): void
    {
        $this->getClassExtensions($class)->remove($name);
    }

    /**
     * Get the class extension collection.
     */
    private function getClassExtensions(string $class): ExtensionsCollection
    {
        return $this->items[$class] ?? ($this->items[$class] = new ExtensionsCollection());
    }

    /**
     * Get the parent class extension collection.
     */
    private function getParentExtensions(string $class): ?ExtensionsCollection
    {
        $parent = get_parent_class($class);

        if ($parent === false) {
            throw new ExtensionHasNoParentException("The extension '$class' has no parent extension.");
        }

        if (! is_subclass_of($parent, Extensible::class)) {
            return null;
        }

        return $this->getClassExtensions($parent);
    }
}
