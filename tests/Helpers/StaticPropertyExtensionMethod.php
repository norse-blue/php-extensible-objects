<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

use NorseBlue\ExtensibleObjects\Contracts\ExtensionMethod;

class StaticPropertyExtensionMethod extends StaticPropertyOBject implements ExtensionMethod
{
    /**
     * @return static callable(): string
     */
    public function __invoke(): callable
    {
        /**
         * Upper case the static property.
         *
         * @return string
         */
        return static function (): string {
            return strtoupper(self::$property);
        };
    }
}
