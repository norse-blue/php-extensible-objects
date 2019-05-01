<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

/**
 * Class DynamicMethodUsingPrivateValue
 *
 * @package NorseBlue\ExtensibleObjects\Tests\Helpers
 *
 * @method int add_to_private(int $operand) Through DynamicMethodUsingPrivateValue extension method.
 * @extends SimpleObject
 */
class DynamicMethodUsingPrivateValue extends SimpleObject implements ExtensionMethod
{
    /**
     * @inheritDoc
     */
    public function __invoke($operand = 0): callable
    {
        /**
         * Add the given operand to the private value.
         *
         * @param int $operand
         *
         * @return int
         */
        return function (int $operand): int {
            return $this->private_value + $operand;
        };
    }
}
