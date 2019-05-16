<?php

namespace NorseBlue\ExtensibleObjects\Tests\Helpers;

class ChildObject extends SimpleObject
{
    /**  @var callable[] The registered extensions. */
    protected static $extensions = [];

    /** @var int */
    private $private_value;
}
