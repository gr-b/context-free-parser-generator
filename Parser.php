<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\EBNFToken;

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

    /**
     * Produces an abstract syntax tree for the given input.
     * @param string $input
     *
     * @return EBNFToken
     */
    public function parse($input)
    {
        if (!is_string($input)) {
            throw new \Exception("Invalid input type.");
        }

        if ($this->getEBNF($input)) {
            throw new \Exception("Syntax Error");
        }

        return $ebnf;
    }

    /**getEBNF - Consumes a representation of an ebnf
     * Produces an EBNFToken holding the production rules
     *
     * @param string $input
     * @return EBNFToken
     */
    private function getEBNF($input)
    {
        $rules = array();

        while ( ($rule = getRule($input)) !== false) {
            $rules[] = $rules;
        }
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
}