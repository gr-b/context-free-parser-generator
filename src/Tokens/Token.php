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
    const TYPE_EBNF = 8;
    const TYPE_RULE = 9;

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
     * Adds statements derived from the current class to the
     * given output array.
     * @param array $output
     */
    abstract public function getStatements(array &$output);
}
