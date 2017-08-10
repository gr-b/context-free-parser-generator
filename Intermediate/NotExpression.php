<?php

namespace ParserGenerator\Intermediate;

class NotExpression extends ExpressionToken
{
    /** @var ExpressionToken */
    private $expression;

    public function __construct(ExpressionToken $expression)
    {
        parent::__construct(IntermediateToken::NOT_EXPRESSION);
        $this->expression = $expression;
    }

    public function getSyntax()
    {
        return "!{$this->expression->getSyntax()}";
    }

}