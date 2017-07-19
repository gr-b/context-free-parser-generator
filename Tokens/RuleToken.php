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

    /** @var Bool $isClassRule */
    private $isClassRule;

    public function __construct($ruleName, Token $expression, $isClassRule = false)
    {
        parent::__construct(Token::TYPE_RULE);
        $this->ruleName = $ruleName;
        $this->expression = $expression;
        $this->isClassRule = $isClassRule;
    }

    public function __toString()
    {
        return ($this->isClassRule ? '@' : '') .
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

    public function map($function, $combiner)
    {
        return $combiner(array(
                $function($this->expression))
        );
    }
}