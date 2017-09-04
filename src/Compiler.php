<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\Token;
use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\TerminalToken;
use ParserGenerator\Tokens\OptionalToken;
use ParserGenerator\Tokens\RepetitionToken;
use ParserGenerator\Tokens\GroupingToken;

use ParserGenerator\Intermediate\IntermediateToken;
use ParserGenerator\Intermediate\FunctionDeclaration;
use ParserGenerator\Intermediate\ArgumentToken;
use Exception;

/**
 * Class Compiler
 * @author Griffin Bishop <grbishop@wpi.edu>
 * @package ParserGenerator
 */

class Compiler
{
    /** @var array $ruleTable */
    private $ruleTable; // Of the form: rulename => rule token

    /**
     * Compiles the given abstract syntax tree from the parser into a
     * Compiler Intermediate Semantic Tree
     *
     * @param EBNFToken $ast
     * The abstract syntax tree output from the parser.
     */
    public function compile(EBNFToken $ast)
    {
        // 1. Generate autoloader from template
        $projectName = self::getParserName($ast);


        $this->ruleTable = $this->getRuleTable($ast);
        echo "Rule table:\n";
        $this->printRuleTable();

        //echo "Class rules:\n";
        $classRules = $this->getClassRules($ast);
        echo self::printTokens($classRules);


        $rules = $ast->getRules();

        echo "Generated functions:\n";

        $functions = array();
        foreach ($rules as $rule) {
            $function = $this->generateGetRule($rule);
            $functions[] = $function->getSyntax();
            echo $function->getSyntax()."\n";
        }
    }

    private function generateGetRule(RuleToken $rule)
    {
        $name = 'get_'.$rule->getName();
        $visbility = FunctionDeclaration::VISIBILITY_PRIVATE;
        $function =  new FunctionDeclaration($name, $visbility);

        $expression = $rule->getExpression();
        $statements = array();
        $expression->getStatements($statements);

        $function->setStatements($statements);
        $function->addArgument(new ArgumentToken('output', true));

        return $function;
    }

    private function printRuleTable()
    {
        foreach ($this->ruleTable as $name => $token) {
            echo "'{$name}' => ". RenderToken::syntax($token) ."\n\n";
        }
    }

    /**
     * Consumes an EBNFToken
     * Produces an array of rule tokens where isClass = true for each token
     * @param EBNFToken $ebnf
     * @throws Exception
     * @return array
     */
    private function getClassRules(EBNFToken $ebnf)
    {
        $rules = $ebnf->getRules();

        $classRules = array();
        foreach ($rules as $rule) {
            /** @var RuleToken $rule */
            if ($rule->isClass()) {
                $classRules[] = $rule;
            }
        }

        return $classRules;
    }

    /**
     * Consumes an EBNFToken
     * Produces an associative array where the each key
     * is the name of a rule in the EBNF
     * @param EBNFToken $ebnf
     * @throws Exception
     * @return array
     */
    private function getRuleTable(EBNFToken $ebnf)
    {
        $rules = $ebnf->getRules();

        $ruleTable = array();
        foreach ($rules as $rule) {
            /** @var RuleToken $rule */
            $ruleName = $rule->getName();
            $ruleTable[$ruleName] = $rule;
        }

        return $ruleTable;
    }

    /**
     * @param array $tokens
     * @return string
     */
    public static function printTokens(array $tokens)
    {
        $string = "[";
        foreach ($tokens as $token) {
            $string .= RenderToken::syntax($token).", ";
        }
        $string .= "]\n";

        return $string;
    }

    private function getParserName(EBNFToken $ast)
    {
        $rules = $ast->getRules();
        $first = array_shift($rules);
        if (!isset($first)) {
            throw new Exception("Compiler: could not find project name in first production rule.\n");
        }
        return $first->getName();
    }
}