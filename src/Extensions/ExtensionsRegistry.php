<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Extensions;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Extension;

final class ExtensionsRegistry
{
    /** @var array<string, ExtensionsCollection> */
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Get the class extension collection.
     *
     * @param string $class
     *
     * @return ExtensionsCollection
     */
    protected function getClassExtensions(string $class): ExtensionsCollection
    {
        if (!isset($this->items[$class])) {
            return $this->items[$class] = new ExtensionsCollection();
        }

        return $this->items[$class];
    }

    /**
     * Get the parent class extension collection.
     *
     * @param string $class
     *
     * @return \NorseBlue\ExtensibleObjects\Extensions\ExtensionsCollection|null
     */
    protected function getParentExtensions(string $class): ?ExtensionsCollection
    {
        $parent = get_parent_class($class);
        if (!is_subclass_of($parent, Extensible::class)) {
            return null;
        }

        return $this->getClassExtensions($parent);
    }

    /**
     * Add a class extension to the registry.
     *
     * @param string $class
     * @param string $name
     * @param Extension $extension
     */
    public function add(string $class, string $name, Extension $extension): void
    {
        $this->getClassExtensions($class)->add($name, $extension);
    }

    /**
     * Get the class extensions collection from the registry.
     *
     * @param string $class
     * @param bool $exclude_parent
     *
     * @return ExtensionsCollection
     */
    public function get(string $class, bool $exclude_parent = false): ExtensionsCollection
    {
        $extensions = self::getClassExtensions($class);

        if (!$exclude_parent) {
            $extensions = $extensions->merge($this->getParentExtensions($class));
        }

        return new ExtensionsCollection($extensions->toArray());
    }

    /**
     * Remove the class extension from the registry.
     *
     * @param string $class
     * @param string $name
     */
    public function remove(string $class, string $name): void
    {
        $this->getClassExtensions($class)->remove($name);
    }
}
