<?php

namespace ParserGenerator\Intermediate;

class ReturnStatement extends StatementToken
{
    /** @var ExpressionToken */
    private $expression;

    public function __construct(ExpressionToken $expression)
    {
        parent::__construct(IntermediateToken::RETURN_STATEMENT);
        $this->expression = $expression;
    }

    public function getSyntax($depth = 0)
    {
        return "return {$this->expression->getSyntax()};";
    }
}