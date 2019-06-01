<?php

declare(strict_types=1);

namespace NorseBlue\ExtensibleObjects\Contracts;

interface Creatable
{
    /**
     * Creates a new instance.
     *
     * @return mixed
     */
    public static function create();
}
