<?php

namespace ParserGenerator;

require __DIR__.'/autoload.php';

$input = <<< EOS
equation = variable, " = ", expression, ";";
expression = sum | variable;

@sum = expression, "+", expression;
@doublesum = "sum:", sum, sum;

variable = "x" | "y" | "z" | "a" | "b" | "c";
EOS;

//$input = '@algebra = test;';

// Abstract Syntax Tree
$parser = new Parser();
$ast = $parser->parse($input);

// Compiler Intermediate Semantic tree
$compiler = new Compiler();
$compiler->compile($ast);
