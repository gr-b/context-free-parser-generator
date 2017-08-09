<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\Token;
use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\TerminalToken;
use ParserGenerator\Tokens\ClassRuleToken;
use ParserGenerator\Tokens\OptionalToken;
use ParserGenerator\Tokens\RepetitionToken;
use ParserGenerator\Tokens\GroupingToken;
use Exception;

/**
 * Class Compiler
 * @author Griffin Bishop <grbishop@wpi.edu>
 * @package ParserGenerator
 */

class Compiler
{
    /** @var Generator $generator */
    private $generator;

    /** @var  array $tokenClasses */
    private $tokenClasses;

    /** @var array $ruleTable */
    private $ruleTable; // Of the form: rulename => rule token

    public function __construct()
    {
    }

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

        echo "Class rules:\n";
        $classRules = $this->getClassRules($ast);
        echo self::printTokens($classRules);

        $classTokenInputs = $this->getInputs($classRules);
    }

    /**
     * Consumes a list of RuleTokens where isClass = true
     * Produces an associative array where each key is the name of
     * exactly one RuleToken and each value is an array specifying
     * the types/inputs accepted by the corresponding RuleToken.
     * @param RuleToken[]
     * isClass must be true for all tokens in the array
     * @return array
     */
    private function getInputs(array $ruleTokens)
    {
        $inputs = array();
        foreach ($ruleTokens as $ruleToken) {
            $inputs = array_merge($inputs, $this->getInput($ruleToken));
        }
        return $inputs;
    }

    /**
     * Consumes a RuleToken where class = true.
     * Produces an associative array with one association:
     * the name of the ruleToken => and array of tokens or strings
     * designating what types the future ClassToken will take
     * as input.
     * @param $ruleToken
     */
    private function getInput($ruleToken)
    {

    }

    private function printRuleTable()
    {
        foreach ($this->ruleTable as $name => $token) {
            echo "'{$name}' => {$token}\n\n";
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
            $string .= self::printToken($token).", ";
        }
        $string .= "]";

        return $string;
    }

    public static function printToken(Token $token)
    {
        $type = $token->getType();

        switch ($type) {
            case Token::TYPE_EBNF:
                /** @var EBNFToken $token */
                $guts = array_map(array('self', 'printToken'), $token->getRules());
                $guts = implode(', ', $guts);
                return "ebnf(".$guts.")";

            case Token::TYPE_RULE:
                /** @var RuleToken $token */
                return "rule(".($token->isClass() ? '@' : '') .
                    $token->getName().", ".self::printToken($token->getExpression()). ";\n)";

            case Token::TYPE_OR:
                /** @var OrToken $token */
                return "or(".self::printToken($token->getLeft()).' | '. self::printToken($token->getRight()).")";

            case Token::TYPE_COMMA:
                /** @var CommaToken $token */
                return "comma(".self::printToken($token->getLeft()).' , '. self::printToken($token->getRight()).")";

            case Token::TYPE_GROUPING:
                /** @var GroupingToken $token */
                return "grouping(".self::printToken($token->getExpression()).")";

            case Token::TYPE_REPETITION:
                /** @var RepetitionToken $token */
                return "repetition(".self::printToken($token->getExpression()).")";

            case Token::TYPE_OPTIONAL:
                /** @var OptionalToken $token */
                return "optional(".self::printToken($token->getExpression()).")";

            case Token::TYPE_TERMINAL:
                /** @var TerminalToken $token */
                return "terminal(\"".$token->getTerminal()."\")";

            case Token::TYPE_IDENTIFIER:
                /** @var IdentifierToken $token */
                return "identifier('".$token->getIdentifier()."')";

            default:
                throw new Exception("Abstract syntax tree is invalid!");
        }
    }

    /**
     * Computes the inputs necessary to create each token class
     * defined in the parser by TokenTokens.
     * Puts this information in $this->tokenClasses.
     * @param Token $ast
     * The token whose class inputs we will create
     * NOTE: Not necessarily a ClassRuleToken object.
     */
    private function computeTokenClasses(Token $ast)
    {

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