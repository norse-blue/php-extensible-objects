<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class StaticExtensionMethod extends SimpleObject implements ExtensionMethod
{
    /**
     * @return static callable(int $operand)
     */
    public function __invoke(): callable
    {
        /**
         * Multiply the given operand by itself.
         *
         * @param int $operand
         *
         * @return int
         */
        return static function (int $operand): int {
            return $operand * $operand;
        };
    }
}
