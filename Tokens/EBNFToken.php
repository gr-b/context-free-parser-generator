<?php

namespace ParserGenerator\Tokens;

/**
 * Class EBNFToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class EBNFToken extends Token
{
    /** @var array $rules - Array(RuleToken) */
    private $rules;

    public function __construct(array $rules)
    {
        parent::__construct(Token::TYPE_EBNF);
        $this->rules = $rules;
    }

    public function getStatements(array &$output)
    {

    }

    public function getRules()
    {
        return $this->rules;
    }
}