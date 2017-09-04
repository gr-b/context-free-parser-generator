<?php

namespace ParserGenerator\Intermediate;

class IfStatement extends StatementToken
{
    /** @var ExpressionToken */
    private $expression;

    /** @var array */
    private $statements;

    public function __construct(ExpressionToken $expression = null, array $statements = null)
    {
        parent::__construct(IntermediateToken::IF_STATEMENT);
        $this->expression = $expression;
        $this->statements = $statements;
    }

    public function getSyntax($depth = 0)
    {
        $tabs = str_repeat("\t", $depth);
        $depth += 1;

        $syntax = "{$tabs}if ({$this->expression->getSyntax()}) {\n";

        foreach ($this->statements as $statement) {
            $syntax .= "{$tabs}\t{$statement->getSyntax($depth)}\n";
        }

        $syntax .= "{$tabs}}";
        return $syntax;
    }

    public function setExpression(ExpressionToken $expression)
    {
        $this->expression = $expression;
    }

    public function addStatement(StatementToken $statement)
    {
        $this->statements[] = $statement;
    }
}
