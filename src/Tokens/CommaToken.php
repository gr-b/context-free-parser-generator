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

    /**
     * CommaToken constructor.
     * @param Token $left
     * @param Token $right
     */
    public function __construct($left, $right)
    {
        parent::__construct(Token::TYPE_COMMA);
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