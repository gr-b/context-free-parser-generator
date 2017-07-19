<?php

namespace ParserGenerator\Tokens;

/**
 * Class Token
 * @author Griffin Bishop <grbishop@wpi.edu>
 */
abstract class Token
{
    const TYPE_COMMA = 1;
    const TYPE_OR = 2;
    const TYPE_IDENTIFIER = 3;
    const TYPE_TERMINAL = 4;
    const TYPE_OPTIONAL = 5;
    const TYPE_REPETITION = 6;
    const TYPE_GROUPING = 7;
    const TYPE_CLASS_RULE = 8;
    const TYPE_EBNF = 9;
    const TYPE_RULE = 10;

    /** @var integer */
    private $type;


    /**
     * @param integer $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * Calls the given $function on every Token stored in the current
     * token.
     * @param Callable $function
     * Calls this function on each applicable Token stored in this token.
     * The function given must take a token.
     * @param Callable $combiner
     * $combiner takes an array of whatever $function returns.
     */
    abstract public function map($function, $combiner);
}
