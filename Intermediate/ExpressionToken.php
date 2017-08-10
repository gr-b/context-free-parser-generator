<?php

namespace ParserGenerator\Intermediate;

abstract class ExpressionToken extends IntermediateToken
{
    public function __construct($type)
    {
        parent::__construct($type);
    }
}
