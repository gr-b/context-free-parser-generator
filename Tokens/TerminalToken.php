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

    public function getStatements(array &$output)
    {

    }

    public function getTerminal()
    {
        return $this->terminal;
    }
}