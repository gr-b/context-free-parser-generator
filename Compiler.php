<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\TerminalToken;
use ParserGenerator\Tokens\OptionalToken;
use ParserGenerator\Tokens\RepetitionToken;
use ParserGenerator\Tokens\ClassToken;
use ParserGenerator\Tokens\TokenToken;
use Exception;

/**
 * Class Compiler
 * @author Griffin Bishop <grbishop@wpi.edu>
 * @package ParserGenerator
 */

class Compiler
{
    /** @var string */
    private $input;

    /** @var string */
    private $state;

    public function __construct()
    {
    }

    private function isInputConsumed()
    {
        return strlen($this->state) === 0;
    }

    /**
     * Produces an abstract syntax tree for the given input.
     * @param string $input
     *
     * @return EBNFToken
     * @throws Exception
     */
    public function parse($input)
    {
        if (!is_string($input)) {
            throw new Exception("Invalid input type.");
        }

        $this->input = $input;
        $this->state = $input;

        if (!$this->getEBNF($output)) {
            $this->syntaxError($input);
        }

        return $output;
    }

    /**getEBNF - Consumes a representation of an ebnf
     * Produces an EBNFToken holding the production rules
     *
     * @return EBNFToken|bool
     */
    private function getEBNF(&$output)
    {
        $rules = array();

        while ($this->getRule($rule)) {
            $rules[] = $rule;
        }

        if (empty($rules)) {
            $this->syntaxError('rule', '\'\'');
        }

        if (!$this->isInputConsumed()) {
            return false;
        }

        $output = new EBNFToken($rules);
        return true;
    }

    private function getRule(&$output)
    {
        if (!$this->getIdentifier($ruleName)) {
            return false;
        }

        if (!$this->scan("\s*=\s*", $equals)) {
            $this->syntaxError('\' = \'', 'rule \''.$ruleName.'\'');
        }

        if (!$this->getExpression($expression)) {
            $this->syntaxError("expression", '\''.$ruleName.' =\'');
        }

        if (!$this->scan("\s*;\s*")) {
            $this->syntaxError('\';\'', 'expression \''.$expression.'\'');
        }

        $output = new RuleToken($ruleName, $expression);
        return true;
    }

    private function getToken(&$output)
    {
        if ($this->scan("\s*@")) {
            if (!$this->getIdentifier($tokenIdentifier)) {
                $this->syntaxError('identifier', '\'@\'');
            }
            $output = new TokenToken($tokenIdentifier);
            return true;
        }
        return false;
    }

    private function getExpression(&$output)
    {
        return $this->getToken($output)
            || $this->getBinaryExpression($output)
            || $this->getTerminal($output)
            || $this->getOptional($output)
            || $this->getRepetition($output)
            || $this->getGrouping($output);

    }

    private function getBinaryExpression(&$output)
    {
        if (!$this->getIdentifier($identifier) && !$this->getTerminal($identifier)) {
            return false;
        }

        if ($this->scan("\s*\|\s*")) {
            if (!$this->getExpression($rightIdentifier)) {
                $this->syntaxError('expression', '\'|\'');
            }
            //echo "Found $identifier | $rightIdentifier\n";
            $output = new OrToken($identifier, $rightIdentifier);
            return true;
        }

        if ($this->scan("\s*,\s*")) {
            if (!$this->getExpression($rightIdentifier)) {
                $this->syntaxError('expression', '\',\'');
            }
            //echo "Found $identifier , $rightIdentifier\n";
            $output = new CommaToken($identifier, $rightIdentifier);
            return true;
        }

        $output = new IdentifierToken($identifier);
        return true;
    }

    private function getTerminal(&$output)
    {
        if ($this->scan('"')) {
            if (!$this->scan('[^"]+', $terminal)) {
                $this->syntaxError('terminal', '\'"\'');
            }
            if (!$this->scan('"')) {
                $this->syntaxError('\'"\'', '\'"'.$terminal[0].'\'');
            }

            $output = new TerminalToken($terminal[0]);
            return true;
        }

        return false;
    }

    private function getOptional(&$output)
    {
        if ($this->scan("\[\s*")) {
            if (!$this->getExpression($expression)) {
                $this->syntaxError('expression', '\'[\'');
            }
            if (!$this->scan("\s*\]")) {
                $this->syntaxError('\']\'', 'expression \''.$expression.'\'');
            }

            $output = new OptionalToken($expression);
            return true;
        }

        return false;
    }

    private function getRepetition(&$output)
    {
        if ($this->scan("{\s*")) {
            if (!$this->getExpression($expression)) {
                $this->syntaxError('expression', '\'{\'');
            }
            if (!$this->scan("\s*}")) {
                $this->syntaxError('\'}\'', 'expression \''.$expression.'\'');
            }

            $output = new RepetitionToken($expression);
            return true;
        }

        return false;
    }

    private function getGrouping(&$output)
    {
        if ($this->scan("\(\s*")) {
            if (!$this->getExpression($expression)) {
                $this->syntaxError('expression', '\'(\'');
            }
            if (!$this->scan("\s*\)")) {
                $this->syntaxError('\')\'', 'expression \''.$expression.'\'');
            }

            $output = new ClassToken($expression);
            return true;
        }

        return false;
    }

    private function getIdentifier(&$output)
    {
        if (!$this->scan("[a-zA-Z]([a-zA-Z]|[0-9]|_)+", $identifier)) {
            return false;
        }

        $output = $identifier[0];
        return true;
    }

    private function scan($expression, &$output = null)
    {
        $delimiter = "\x03";
        $flags = 'A'; // A: anchored

        $pattern = "{$delimiter}{$expression}{$delimiter}{$flags}";

        if (preg_match($pattern, $this->state, $matches) !== 1) {
            return false;
        }

        $output = $matches;
        $length = strlen($matches[0]);
        $this->state = (string)substr($this->state, $length);

        return true;
    }

    public function syntaxError($expectation, $after)
    {
        throw new Exception("Expected {$expectation} after {$after}, got '{$this->state}' instead\n");
    }
}