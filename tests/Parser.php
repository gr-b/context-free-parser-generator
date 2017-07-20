<?php

namespace ParserGenerator;

use ParserGenerator\Tokens\CommaToken;
use ParserGenerator\Tokens\EBNFToken;
use ParserGenerator\Tokens\IdentifierToken;
use ParserGenerator\Tokens\OrToken;
use ParserGenerator\Tokens\RuleToken;
use ParserGenerator\Tokens\TerminalToken;

require TESTPHP_TESTS_DIRECTORY.'/autoload.php';




$input = null;

// Test
$parser = new Parser();
$output = $parser->parse($input);
$output = $output->__toString();


// Input
$input = "rule = test;";

// Output
$output = "rule = test;\n";


// Input
$input = "rule =  thing ; thing = test;";

// Output
$output = "rule = thing;\nthing = test;\n";


// Input
$input = "rule =  thing | test;";

// Output
$output = "rule = thing | test;\n";


// Input
$input = "rule =  thing , test;";

// Output
$output = "rule = thing , test;\n";


// Input
$input = "rule =  ( thing | test );";

// Output
$output = "rule = ( thing | test );\n";


// Input
$input = "rule =  { thing | test };";

// Output
$output = "rule = { thing | test };\n";


// Input
$input = "rule =  thing | \"test\";";

// Output
$output = "rule = thing | \"test\";\n";


// Input
$input = "rule =  thing | \"test\";";

// Output
$output = "rule = thing | \"test\";\n";


// Input
$input = "@rule = thing;";

// Output
$output = "@rule = thing;\n";


// Test
$parser = new Parser();
$output = $parser->parse($input);


// Input
$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | variable;

@sum = expression, "+", expression;

EOS;
//@variable = "x" | "y" | "z" | "a" | "b" | "c";

// Output
$output = new EBNFToken(
    array(
        new RuleToken(
            "equation",
            new CommaToken(
                new IdentifierToken("variable"),
                new CommaToken(
                    new TerminalToken(" = "),
                    new CommaToken(
                        new IdentifierToken("expression"),
                        new TerminalToken(";")
                    )
                )
            )
        ),
        new RuleToken(
            "expression",
            new OrToken(
                new IdentifierToken("sum"),
                new IdentifierToken("variable")
            )
        ),
        new RuleToken(
            "sum",
            new CommaToken(
                new IdentifierToken("expression"),
                new CommaToken(
                    new TerminalToken("+"),
                    new IdentifierToken("expression")
                )
            ),
            true
        ),
    )
);
