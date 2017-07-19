<?php

namespace ParserGenerator\Tokens;

/**
 * Class GroupingToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class GroupingToken extends Token
{
    /** @var $expression Token */
    private $expression;

    public function __construct($expression)
    {
        parent::__construct(Token::TYPE_GROUPING);
        $this->expression = $expression;
    }

    public function getExpression()
    {
        return $this->expression;
    }

    public function __toString()
    {
        return "( ".$this->expression." )";
    }

    public function map($function, $combiner)
    {
        return $combiner(array(
                $function($this->expression))
        );
    }
}
