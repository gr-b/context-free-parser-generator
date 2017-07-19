<?php

namespace ParserGenerator\Tokens;

/**
 * Class RuleToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class RuleToken extends Token
{
    /** @var string $ruleName */
    private $ruleName;

    /** @var Token $expression */
    private $expression;

    /** @var Bool $isClass */
    private $isClass;

    public function __construct($ruleName, Token $expression, $isClass = false)
    {
        parent::__construct(Token::TYPE_RULE);
        $this->ruleName = $ruleName;
        $this->expression = $expression;
        $this->isClass = $isClass;
    }

    public function __toString()
    {
        return ($this->isClass ? '@' : '') .
            $this->ruleName." = ".$this->expression->__toString(). ";\n";
    }

    public function getName()
    {
        return $this->ruleName;
    }

    public function getExpression()
    {
        return $this->expression;
    }

    public function isClass()
    {
        return $this->isClass;
    }

    public function collectClassRuleTokens()
    {
        $tokens  = $this->expression->collectClassRuleTokens();

        if ($this->isClass) {
            $tokens[] = $this;
        }
        return $tokens;
    }
}