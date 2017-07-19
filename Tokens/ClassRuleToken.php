<?php

namespace ParserGenerator\Tokens;

/**
 * Class ClassRuleToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class ClassRuleToken extends Token
{
    /** @var string $ruleName */
    private $ruleName;

    /** @var Token $expression */
    private $expression;

    public function __construct($ruleName, Token $expression)
    {
        parent::__construct(Token::TYPE_CLASS_RULE);
        $this->ruleName = $ruleName;
        $this->expression = $expression;
    }

    public function __toString()
    {
        return '@'.$this->ruleName." = ".$this->expression->__toString(). ";\n";
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