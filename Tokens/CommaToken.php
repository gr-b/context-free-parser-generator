<?php

namespace ParserGenerator\Tokens;

/**
 * Class CommaToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class CommaToken extends Token
{
    /** @var $left Token */
    private $left;

    /** @var $right Token */
    private $right;

    public function __construct($left, $right)
    {
        parent::__construct(Token::TYPE_COMMA);
        $this->left = $left;
        $this->right = $right;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function __toString()
    {
        return $this->left." , ".$this->right;
    }

    public function map($function, $combiner)
    {
        return $combiner(array(
            $function($this->left),
            $function($this->right))
        );
    }
}