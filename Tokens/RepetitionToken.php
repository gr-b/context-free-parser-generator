<?php

namespace ParserGenerator\Tokens;

/**
 * Class RepetitionToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class RepetitionToken extends Token
{
    /** @var $expression Token */
    private $expression;

    public function __construct($expression)
    {
        parent::__construct(Token::TYPE_REPETITION);
        $this->expression = $expression;
    }

    public function __toString()
    {
        return "{ ".$this->expression." }";
    }
}
