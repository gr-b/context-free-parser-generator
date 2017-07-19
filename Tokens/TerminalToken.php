<?php

namespace ParserGenerator\Tokens;

/**
 * Class TerminalToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class TerminalToken extends Token
{
    /** @var $terminal string */
    private $terminal;

    public function __construct($terminal)
    {
        parent::__construct(Token::TYPE_TERMINAL);
        $this->terminal = $terminal;
    }

    public function __toString()
    {
        return '"'.$this->terminal.'"';
    }

    public function getTerminal()
    {
        return $this->terminal;
    }

    public function collectClassRuleTokens()
    {
        return array();
    }
}