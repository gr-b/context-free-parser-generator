<?php

namespace ParserGenerator\Tokens;

/**
 * Class ClassToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class TokenToken extends Token
{
    /** @var $identifier string */
    private $identifier;

    public function __construct($identifier)
    {
        parent::__construct(Token::TYPE_TOKEN);
        $this->identifier = $identifier;
    }

    public function __toString()
    {
        return '@'.$this->identifier;
    }
}