<?php

namespace ParserGenerator\Tokens;

/**
 * Class CommaToken
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

    public function __toString()
    {
        return $this->left." , ".$this->right;
    }
}