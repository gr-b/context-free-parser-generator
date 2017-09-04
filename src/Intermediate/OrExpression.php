<?php

namespace ParserGenerator\Intermediate;

class OrExpression extends ExpressionToken
{
    /** @var ExpressionToken */
    private $left;

    /** @var ExpressionToken */
    private $right;

    public function __construct(ExpressionToken $left, ExpressionToken $right)
    {
        parent::__construct(IntermediateToken::OR_EXPRESSION);
        $this->left = $left;
        $this->right = $right;
    }

    public function getSyntax()
    {
        return "{$this->left->getSyntax()} || {$this->right->getSyntax()}";
    }

}