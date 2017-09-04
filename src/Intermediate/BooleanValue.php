<?php

namespace ParserGenerator\Intermediate;

class BooleanValue extends ExpressionToken
{
    /** @var bool */
    private $value;

    /**
     * BooleanValue constructor.
     * @param bool $value
     */
    public function __construct($value)
    {
        parent::__construct(IntermediateToken::BOOLEAN_VALUE);
        $this->value = $value;
    }

    public function getSyntax()
    {
        return ($this->value ? 'true' : 'false');
    }
}