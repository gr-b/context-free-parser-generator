<?php

namespace ParserGenerator\Tokens;

use ParserGenerator\Intermediate\FunctionDeclaration;
use ParserGenerator\Intermediate\ArgumentToken;

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

    public function generateGetFunction(array &$functions)
    {
        $name = 'get_'.$this->getName();
        $visbility = FunctionDeclaration::VISIBILITY_PRIVATE;
        $function =  new FunctionDeclaration($name, $visbility);

        $expression = $this->getExpression();
        $statements = array();
        $expression->getStatements($statements, $functions);

        $function->setStatements($statements);
        $function->addArgument(new ArgumentToken('output', true));

        return $function;
    }

    public function getStatements(array &$output, array &$functions)
    {

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
}