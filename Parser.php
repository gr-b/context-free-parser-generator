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
use ParserGenerator\Tokens\GroupingToken;
use Exception;

/**
 * Class Parser
 * @package ParserGenerator
 *
 * ebnf = { grammar }
 *
 * grammar = { rule } ;
 *
 * rule = lhs , "=" , rhs , ";" ;
 *
 * lhs = identifier ;
 * rhs = identifier
 * | terminal
 * | "[" , rhs , "]"
 * | "{" , rhs , "}"
 * | "(" , rhs , ")"
 * | rhs , "|" , rhs
 * | rhs , "," , rhs ;
 *
 * identifier = letter , { letter | digit | "_" } ;
 *
 * terminal = "'" , character , { character } , "'"
 * | '"' , character , { character } , '"' ;
 *
 * letter = "A" | "B" | "C" | "D" | "E" | "F" | "G"
 * | "H" | "I" | "J" | "K" | "L" | "M" | "N"
 * | "O" | "P" | "Q" | "R" | "S" | "T" | "U"
 * | "V" | "W" | "X" | "Y" | "Z" | "a" | "b"
 * | "c" | "d" | "e" | "f" | "g" | "h" | "i"
 * | "j" | "k" | "l" | "m" | "n" | "o" | "p"
 * | "q" | "r" | "s" | "t" | "u" | "v" | "w"
 * | "x" | "y" | "z" ;
 *
 * digit = "0" | "1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" ;
 *
 * symbol = "[" | "]" | "{" | "}" | "(" | ")" | "<" | ">"
 * | "'" | '"' | "=" | "|" | "." | "," | ";" ;
 *
 * character = letter | digit | symbol | "_" ;
 *
 *
 */

class Parser
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
            $this->syntaxError('=', 'rule name '.$ruleName);
            //throw new Exception("Expected '=' after rule name $ruleName, got {$this->state} instead\n");
        }

        if (!$this->getExpression($expression)) {
            $this->syntaxError("expression", $ruleName.' =');
            //throw new Exception("Expected expression after '$ruleName =', got {$this->state} instead \n");
        }

        if (!$this->scan("\s*;\s*")) {
            $this->syntaxError(';', 'expression '.$expression);
            //throw new Exception("Expected ';' after expression $expression, got {$this->state} instead\n");
        }

        $output = new RuleToken($ruleName, $expression);
        return true;
    }

    private function getExpression(&$output)
    {
        return $this->getBinaryExpression($output)
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
                $this->syntaxError('expresison', '|');
                //throw new Exception("Expected expression after '|', got {$this->state} instead\n");
            }
            //echo "Found $identifier | $rightIdentifier\n";
            $output = new OrToken($identifier, $rightIdentifier);
            return true;
        }

        if ($this->scan("\s*,\s*")) {
            if (!$this->getExpression($rightIdentifier)) {
                $this->syntaxError('expression', ',');
                //throw new Exception("Expected expression after ',', got {$this->state} instead \n");
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
            if (!$this->scan('[a-zA-Z0-9_]+', $terminal)) {
                $this->syntaxError('terminal', '"');
                //throw new Exception("Expected terminal after ' \" ', got {$this->state} instead\n");
            }
            if (!$this->scan('"')) {
                $this->syntaxError('"', '"'.terminal[0]);
                //throw new Exception("Expected ' \" ' after ' \"{$terminal[0]} ', got {$this->state} instead");
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
                $this->syntaxError('expression', '[');
                //throw new Exception("Expected expression after ' [ ', got {$this->state} instead");
            }
            if (!$this->scan("\s*\]")) {
                $this->syntaxError(']', 'expression '.$expression);
                //throw new Exception("Expected ' ] ' after expression $expression, got {$this->state} instead");
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
                $this->syntaxError('expression', '{');
                //throw new Exception("Expected expression after ' { ', got {$this->state} instead");
            }
            if (!$this->scan("\s*}")) {
                $this->syntaxError('}', 'expression '.$expression);
                //throw new Exception("Expected ' } ' after expression $expression, got {$this->state} instead");
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
                $this->syntaxError('expression', '(');
                //throw new Exception("Expected expression after ' ( ', got {$this->state} instead");
            }
            if (!$this->scan("\s*\)")) {
                $this->syntaxError(')', 'expression '.$expression);
                //throw new Exception("Expected ' ) ' after expression $expression, got {$this->state} instead");
            }

            $output = new GroupingToken($expression);
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

    private function syntaxError($expectation, $after)
    {
        throw new Exception("Expected ' {$expectation} ' after ' {$after} ', got {$this->state} instead\n");
    }
}