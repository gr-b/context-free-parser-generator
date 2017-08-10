<?php

namespace ParserGenerator\Intermediate;

class FunctionToken extends IntermediateToken
{
    const VISIBILITY_PUBLIC = 0;
    const VISIBILITY_PRIVATE = 1;

    /** @var integer */
    private $visibility; // only public or private

    /** @var string */
    private $name;

    /** @var array */
    private $arguments;

    /** @var array */
    private $statements;

    public function __construct($name, $visibility)
    {
        parent::__construct(IntermediateToken::FUNCTION_TOKEN);
        $this->name = $name;
        $this->visibility = $visibility;
        $this->statements = array();
    }

    public function getSyntax()
    {
        $syntax = '';
        if ($this->visibility) {
            $syntax .= 'private ';
        } else {
            $syntax .= 'public ';
        }

        $syntax .= "function {$this->name} (";
        for ($i = 0; $i < count($this->arguments); $i++) {
            $argument = $this->arguments[$i];
            $syntax .= $argument->getSyntax();
            if ($i != count($this->arguments) - 1) {
                $syntax .= ', ';
            }
        }
        $syntax .= ")\n{\n\t";

        foreach ($this->statements as $statement) {
            $syntax .= $statement->getSyntax();
        }

        $syntax .= "\n}";

        return $syntax;
    }


    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function addArgument(ArgumentToken $argument)
    {
        $this->arguments[] = $argument;
    }

    public function setStatements(array $statements)
    {
        $this->statements = $statements;
    }

    public function addStatement(Statement $statement)
    {
        $this->statements[] = $statement;
    }
}