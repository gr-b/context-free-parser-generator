<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\Token;
use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\TerminalToken;
use ParserGenerator\Tokens\TokenToken;
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
        $this->generator->generateAutoloader();
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