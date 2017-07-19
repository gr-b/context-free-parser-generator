<?php

namespace ParserGenerator;

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
