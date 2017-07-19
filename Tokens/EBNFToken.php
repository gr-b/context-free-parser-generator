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

    public function __toString()
    {
        $EBNF = '';
        foreach ($this->rules as $rule) {
            $EBNF .= $rule->__toString();
        }

        return $EBNF;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function collectClassRuleTokens()
    {
        $results = array();
        foreach ($this->rules as $rule) {
            $results = array_merge($results, $rule->collectClassRuleTokens());
        }

        return $results;
    }
}