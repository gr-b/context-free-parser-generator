<?php

namespace ParserGenerator\Tokens;

use ParserGenerator\Intermediate\ArgumentToken;
use ParserGenerator\Intermediate\BooleanValue;
use ParserGenerator\Intermediate\CallExpression;
use ParserGenerator\Intermediate\IfStatement;
use ParserGenerator\Intermediate\NotExpression;
use ParserGenerator\Intermediate\ReturnStatement;

/**
 * Class IdentifierToken
 * @author Griffin Bishop <grbishop@wpi.edu>
 */
class IdentifierToken extends Token
{
    /** @var $identifier string */
    private $identifier;

    public function __construct($identifier)
    {
        parent::__construct(Token::TYPE_IDENTIFIER);
        $this->identifier = $identifier;
    }

    public function getStatements(array &$output)
    {
        $ifToken = new IfStatement();

        $getRuleName = "get_{$this->identifier}";

        $ifToken->setExpression(
            new NotExpression(
                new CallExpression(
                    array('this', $getRuleName),
                    array(
                        new ArgumentToken('output', true)
                    )
                )
            )
        );

        /** @noinspection PhpParamsInspection */
        $ifToken->addStatement(
            new ReturnStatement(
                new BooleanValue(false)
            )
        );

        $output[] = $ifToken;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}