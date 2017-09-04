<?php

namespace ParserGenerator\Intermediate;

abstract class IntermediateToken
{
    const FUNCTION_DECLARATION = 0;
    const ARGUMENT_TOKEN = 1;
    const IF_STATEMENT = 2;
    const NOT_EXPRESSION = 3;
    const CALL_EXPRESSION = 4;
    const RETURN_STATEMENT = 5;
    const BOOLEAN_VALUE = 6;
    const OR_EXPRESSION = 7;

    /** @var integer */
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    abstract public function getSyntax();

}