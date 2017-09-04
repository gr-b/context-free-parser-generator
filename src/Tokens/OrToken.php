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

    /**
     * OrToken constructor.
     * @param Token $left
     * @param Token $right
     */
    public function __construct($left, $right)
    {
        parent::__construct(Token::TYPE_OR);
        $this->left = $left;
        $this->right = $right;
    }

    public function getStatements(array &$output)
    {

    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getRight()
    {
        return $this->right;
    }
}