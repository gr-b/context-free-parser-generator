<?php

namespace ParserGenerator;

require __DIR__.'/autoload.php';


$input = 'rule = { thing }; thing = "a" | "b" | "c";';

$parser = new Parser();
$output = $parser->parse($input);

echo $output;
