<?php

namespace ParserGenerator;

require __DIR__.'/autoload.php';


$input = 'rule =  thing | "test";';

$parser = new Parser();
$output = $parser->parse($input);

echo "Output: $output\n";
