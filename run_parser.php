<?php

namespace ParserGenerator;

require __DIR__.'/autoload.php';

/* Example ebnf:
algebraic equations

equations = { equation };
equation = lhs, " = ", expression, ";";
lhs = variable;
expression = sum | difference | product | quotient | group | variable;

sum = expression, "+", expression;
difference = expression, "-", expression;
product = expression, "*", expression;
quotient = expression, "/", expression;
group = "(", expression, ")";

variable = "x" | "y" | "z" | "a" | "b" | "c";

*/


$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | variable;

@sum = expression, "+", expression;

variable = "x" | "y" | "z";
EOS;

//$input = '@rule = test;';

$parser = new Parser();
$output = $parser->parse($input);

echo "Output: $output\n";





// Simple
$input = <<< EOS
equations = { equation };
equation = variable, " = ", expression, ";";
expression = sum | difference | product | quotient | group | variable;

sum = expression, "+", expression;
difference = expression, "-", expression;
product = expression, "*", expression;
quotient = expression, "/", expression;
group = "(", expression, ")";

variable = "x" | "y" | "z" | "a" | "b" | "c";
EOS;


// Simpler
$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | product | group | variable;

sum = expression, "+", expression;
product = expression, "*", expression;
group = "(", expression, ")";

variable = "x" | "y" | "z" | "a" | "b" | "c";
EOS;
