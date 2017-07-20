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

    public function collectClassRuleTokens()
    {
        //echo "Left: ".gettype($this->left).": {$this->left}\n";
        //echo "Right: ".gettype($this->right).": {$this->right}\n\n";
        return array_merge(
            $this->left->collectClassRuleTokens(),
            $this->right->collectClassRuleTokens()
        );
    }
}