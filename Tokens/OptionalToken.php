<?php

namespace ParserGenerator\Tokens;

/**
 * Class OptionalToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class OptionalToken extends Token
{
    /** @var $expression Token */
    private $expression;

    public function __construct($expression)
    {
        parent::__construct(Token::TYPE_OPTIONAL);
        $this->expression = $expression;
    }

    public function __toString()
    {
        return "[ ".$this->expression." ]";
    }
}
