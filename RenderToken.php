<?php
/**
 * Created by PhpStorm.
 * User: gbishop
 * Date: 8/9/17
 * Time: 5:39 PM
 */

namespace ParserGenerator;

use ParserGenerator\Tokens\Token;
use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\TerminalToken;
use ParserGenerator\Tokens\OptionalToken;
use ParserGenerator\Tokens\RepetitionToken;
use ParserGenerator\Tokens\GroupingToken;
use Exception;

class RenderToken
{
    public static function semantics(Token $token)
    {
        $type = $token->getType();

        switch ($type) {
            case Token::TYPE_EBNF:
                /** @var EBNFToken $token */
                $guts = array_map(array('self', 'semantics'), $token->getRules());
                $guts = implode(', ', $guts);
                return "ebnf(".$guts.")";

            case Token::TYPE_RULE:
                /** @var RuleToken $token */
                return "rule(".($token->isClass() ? '@' : '') .
                    $token->getName().", ".self::semantics($token->getExpression()). ";\n)";

            case Token::TYPE_OR:
                /** @var OrToken $token */
                return "or(".self::semantics($token->getLeft()).' | '. self::semantics($token->getRight()).")";

            case Token::TYPE_COMMA:
                /** @var CommaToken $token */
                return "comma(".self::semantics($token->getLeft()).' , '. self::semantics($token->getRight()).")";

            case Token::TYPE_GROUPING:
                /** @var GroupingToken $token */
                return "grouping(".self::semantics($token->getExpression()).")";

            case Token::TYPE_REPETITION:
                /** @var RepetitionToken $token */
                return "repetition(".self::semantics($token->getExpression()).")";

            case Token::TYPE_OPTIONAL:
                /** @var OptionalToken $token */
                return "optional(".self::semantics($token->getExpression()).")";

            case Token::TYPE_TERMINAL:
                /** @var TerminalToken $token */
                return "terminal(\"".$token->getTerminal()."\")";

            case Token::TYPE_IDENTIFIER:
                /** @var IdentifierToken $token */
                return "identifier('".$token->getIdentifier()."')";

            default:
                throw new Exception("Abstract syntax tree is invalid!");
        }
    }

    public static function syntax(Token $token)
    {
        $type = $token->getType();

        switch ($type) {
            case Token::TYPE_EBNF:
                /** @var EBNFToken $token */
                $guts = array_map(array('self', 'syntax'), $token->getRules());
                $guts = implode('', $guts);
                return $guts;

            case Token::TYPE_RULE:
                /** @var RuleToken $token */
                return ($token->isClass() ? '@' : '') .
                    $token->getName()." = ".self::syntax($token->getExpression()). ";\n";

            case Token::TYPE_OR:
                /** @var OrToken $token */
                return self::syntax($token->getLeft()).' | '. self::syntax($token->getRight());

            case Token::TYPE_COMMA:
                /** @var CommaToken $token */
                return self::syntax($token->getLeft()).' , '. self::syntax($token->getRight());

            case Token::TYPE_GROUPING:
                /** @var GroupingToken $token */
                return '( '.self::syntax($token->getExpression()).' )';

            case Token::TYPE_REPETITION:
                /** @var RepetitionToken $token */
                return '{ '.self::syntax($token->getExpression()).' }';

            case Token::TYPE_OPTIONAL:
                /** @var OptionalToken $token */
                return '['.self::syntax($token->getExpression()).'}';

            case Token::TYPE_TERMINAL:
                /** @var TerminalToken $token */
                return '"'.$token->getTerminal().'"';

            case Token::TYPE_IDENTIFIER:
                /** @var IdentifierToken $token */
                return $token->getIdentifier();

            default:
                throw new Exception("Abstract syntax tree is invalid!");
        }
    }
}