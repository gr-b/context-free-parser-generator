<?php

namespace ParserGenerator\Intermediate;

class ArgumentToken extends IntermediateToken
{
    /** @var string */
    private $name;

    /** @var string */
    private $argumentType;

    /** @var boolean */
    private $isReference;

    public function __construct($name, $isReference, $argumentType = '')
    {
        parent::__construct(IntermediateToken::ARGUMENT_TOKEN);
        $this->name = $name;
        $this->isReference = $isReference;
        $this->argumentType = $argumentType;
    }

    public function getSyntax()
    {
        $typePrefix = (($this->argumentType != '') ? $this->argumentType . ' ' : '');
        $name = ($this->isReference ? '&' : '') . '$' . $this->name;

        return $typePrefix.$name;
    }
}