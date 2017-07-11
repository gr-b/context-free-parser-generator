<?php

namespace ParserGenerator\Tokens;

/**
 * Class IdentifierToken
 */

class IdentifierToken extends Token
{
    /** @var $identifier string */
    private $identifier;

    public function __construct($identifier)
    {
        parent::__construct(Token::TYPE_IDENTIFIER);
        $this->identifier = $identifier;
    }

    public function __toString()
    {
        return ''.$this->identifier;
    }
}