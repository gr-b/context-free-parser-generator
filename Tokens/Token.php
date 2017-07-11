<?php

namespace ParserGenerator\Tokens;

abstract class Token
{
    const TYPE_COMMA = 1;
    const TYPE_OR = 2;
    const TYPE_IDENTIFIER = 3;
    const TYPE_TERMINAL = 4;
    const TYPE_OPTIONAL = 5;
    const TYPE_REPETITION = 6;
    const TYPE_GROUPING = 7;

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
}
