<?php

namespace ParserGenerator;

require __DIR__.'/autoload.php';

$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | variable;

@sum = expression, "+", expression;
product = expression, "*", expression;

@variable = "x" | "y" | "z" | "a" | "b" | "c";
EOS;

$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | variable;

@sum = expression, "+", expression;

variable = "x" | "y" | "z";
EOS;


//$input = '@algebra = test;';

// Abstract Syntax Tree
$parser = new Parser();
$ast = $parser->parse($input);

// Compiler Intermediate Semantic tree
$compiler = new Compiler();
$compiler->compile($ast);
