<?php

namespace ParserGenerator\Intermediate;

class CallExpression extends ExpressionToken
{
    /** @var array */
    private $handle;

    /** @var array */
    private $arguments;

    public function __construct(array $handle, array $arguments)
    {
        parent::__construct(IntermediateToken::CALL_EXPRESSION);
        $this->handle = $handle;
        $this->arguments = $arguments;
    }

    public function getSyntax()
    {
        $syntax = '';

        if ($this->handle[0] == 'this') {
            $syntax .= "\$this->{$this->handle[1]}(";
        } else {
            $syntax .= "\${$this->handle[0]}(";
        }

        for ($i = 0; $i < count($this->arguments); $i++) {
            $argument = $this->arguments[$i];
            $syntax .= $argument->getSyntax();
            if ($i != count($this->arguments) - 1) {
                $syntax .= ', ';
            }
        }

        $syntax .= ")";
        return $syntax;
    }
}
