<?php

namespace ParserGenerator\Tokens;

/**
 * Class IdentifierToken
 * @author Griffin Bishop <grbishop@wpi.edu>
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

    public function map($function, $combiner)
    {
        return $combiner(array(
                $function())
        );
    }
}