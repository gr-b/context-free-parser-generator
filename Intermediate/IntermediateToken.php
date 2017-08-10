<?php

namespace ParserGenerator\Intermediate;

abstract class IntermediateToken
{
    const FUNCTION_TOKEN = 0;
    const ARGUMENT_TOKEN = 1;

    /** @var integer */
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    abstract public function getSyntax();

}