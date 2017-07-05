<?php

namespace ParserGenerator\Tokens;

abstract class Token
{
    const TYPE_EBNF = 1;

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
