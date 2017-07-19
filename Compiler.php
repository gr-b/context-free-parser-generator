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
        $this->generator = new Generator($projectName);
        //$this->generator->generateAutoloader();

        $TokenTokens = $this->discoverTokenTokens($ast);
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

    /**
     * Performs a depth first search to find TokenTokens.
     * Returns a list of ClassRuleToken objects.
     * @param Token $token
     * @return Token[]
     */
    private function discoverTokenTokens(Token $token)
    {
        $type = $token->getType();

        switch ($type) {
            case Token::TYPE_EBNF:
                /** @var EBNFToken $token */
                return array_map(array($this, 'discoverTokenTokens'), $token->getRules());

            case Token::TYPE_RULE:
                /** @var RuleToken $token */
                return $this->discoverTokenTokens($token->getExpression());

            case Token::TYPE_OR:
                /** @var OrToken $token */
                return array_merge(
                    $this->discoverTokenTokens($token->getLeft()),
                    $this->discoverTokenTokens($token->getRight())
                );

            case Token::TYPE_COMMA:
                /** @var CommaToken $token */
                return array_merge(
                    $this->discoverTokenTokens($token->getLeft()),
                    $this->discoverTokenTokens($token->getRight())
                );

            case Token::TYPE_GROUPING:
                /** @var GroupingToken $token */
                return $this->discoverTokenTokens($token->getExpression());

            case Token::TYPE_OPTIONAL:
                /** @var OptionalToken $token */
                return $this->discoverTokenTokens($token->getExpression());

            case Token::TYPE_REPETITION:
                /** @var RepetitionToken $token */
                return $this->discoverTokenTokens($token->getExpression());

            case Token::TYPE_IDENTIFIER:
                /** @var IdentifierToken $token */
                return array();

            case Token::TYPE_TERMINAL:
                /** @var TerminalToken $token */
                return array();

            case Token::TYPE_TOKEN_CLASS:
                /** @var ClassRuleToken $token */
                return array($token);

        }
    }

    private function getParserName(EBNFToken $ast)
    {
        $rules = $ast->getRules();
        $first = array_pop($rules);
        if (!isset($first)) {
            throw new Exception("Compiler: could not find project name in first production rule.\n");
        }
        return $first->getName();
    }
}