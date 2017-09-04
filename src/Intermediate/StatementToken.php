<?php

namespace ParserGenerator\Intermediate;

abstract class StatementToken extends IntermediateToken
{
    public function __construct($type)
    {
        parent::__construct($type);
    }
}