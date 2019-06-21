<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\Extensible;
use NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethods;

/**
 * @method int add_to_private(int $operand)
 * @method int subtract_from_protected(int $operand)
 * @method int static_extension(int $operand)
 */
class SimpleObject implements Extensible
{
    use HandlesExtensionMethods;

    /** @var int */
    private $private_value;

    /** @var int */
    protected $protected_value;

    /**
     * SimpleObject constructor.
     *
     * @param int $private_value
     * @param int $protected_value
     */
    public function __construct(int $private_value = 0, int $protected_value = 0)
    {
        $this->private_value = $private_value;
        $this->protected_value = $protected_value;
    }

    /**
     * @return int
     */
    public function getPrivateValue(): int
    {
        return $this->private_value;
    }

    /**
     * @param int $private_value
     */
    public function setPrivateValue(int $private_value): void
    {
        $this->private_value = $private_value;
    }

    /**
     * @return int
     */
    public function getProtectedValue(): int
    {
        return $this->protected_value;
    }

    /**
     * @param int $protected_value
     */
    public function setProtectedValue(int $protected_value): void
    {
        $this->protected_value = $protected_value;
    }
}
