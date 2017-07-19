<?php

namespace ParserGenerator\Tokens;

/**
 * Class EBNFToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class EBNFToken
{
    /** @var array $rules - Array(RuleToken) */
    private $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function __toString()
    {
        $EBNF = '';
        foreach ($this->rules as $rule) {
            $EBNF .= $rule->__toString();
        }

        return $EBNF;
    }
}