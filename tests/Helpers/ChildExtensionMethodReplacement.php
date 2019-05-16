<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

/**
 * Class ChildExtensionMethodReplacement
 *
 * @package NorseBlue\ExtensibleObjects\Tests\Helpers
 *
 * @extends ChildObject
 */
class ChildExtensionMethodReplacement extends SimpleObject implements ExtensionMethod
{
    /**
     * @return callable(int $operand)
     */
    public function __invoke(): callable
    {
        /**
         * Subtract the given operand from the protected value.
         *
         * @param int $operand
         *
         * @return int
         */
        return function (int $operand): int {
            return $this->protected_value - $operand - $operand;
        };
    }
}
