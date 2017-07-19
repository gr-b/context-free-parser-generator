<?php

namespace ParserGenerator\Tokens;

/**
 * Class OrToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */

class OrToken extends Token
{
    /** @var $left Token */
    private $left;

    /** @var $right Token */
    private $right;

    public function __construct($left, $right)
    {
        parent::__construct(Token::TYPE_OR);
        $this->left = $left;
        $this->right = $right;
    }

    public function __toString()
    {
        return $this->left." | ".$this->right;
    }
}