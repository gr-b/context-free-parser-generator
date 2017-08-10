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

    public function getStatements(array &$output)
    {

    }

    public function getExpression()
    {
        return $this->expression;
    }
}
