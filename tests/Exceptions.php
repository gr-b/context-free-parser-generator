<?php

namespace ParserGenerator;

require TESTPHP .'/autoload.php';
use Exception;

$input = null;

// Test
$parser = new Parser();
$output = $parser->parse($input);
$output = RenderToken::syntax($output);


// Input
$input = "";

// Output
throw new Exception("Expected rule after '', got '' instead\n");


// Input
$input = "rule ";

// Output
throw new Exception("Expected ' = ' after rule 'rule', got ' ' instead\n");


// Input
$input = "rule = ";

// Output
throw new Exception("Expected expression after 'rule =', got '' instead\n");


// Input
$input = "rule = otherRule";

// Output
throw new Exception("Expected ';' after expression 'otherRule', got '' instead\n");


// Input
$input = "rule = @";

// Output
throw new Exception("Expected expression after 'rule =', got '@' instead\n");


// Input
$input = "rule = otherRule | ";

// Output
throw new Exception("Expected expression after '|', got '' instead\n");


// Input
$input = "rule = otherRule , ";

// Output
throw new Exception("Expected expression after ',', got '' instead\n");


// Input
$input = "rule = \"";

// Output
throw new Exception("Expected terminal after '\"', got '' instead\n");


// Input
$input = "rule = \"test";

// Output
throw new Exception("Expected '\"' after '\"test', got '' instead\n");


// Input
$input = "rule = [";

// Output
throw new Exception("Expected expression after '[', got '' instead\n");


// Input
$input = "rule = [ expr ";

// Output
throw new Exception("Expected ']' after expression 'expr', got ' ' instead\n");


// Input
$input = "rule = {";

// Output
throw new Exception("Expected expression after '{', got '' instead\n");


// Input
$input = "rule = { expr ";

// Output
throw new Exception("Expected '}' after expression 'expr', got ' ' instead\n");


// Input
$input = "rule = (";

// Output
throw new Exception("Expected expression after '(', got '' instead\n");


// Input
$input = "rule = ( expr ";

// Output
throw new Exception("Expected ')' after expression 'expr', got ' ' instead\n");


// Input
$input = "rule = @expr ";

// Output
throw new Exception("Expected expression after 'rule =', got '@expr ' instead\n");


// Input
$input = "rule@ = expr;";

// Output
throw new Exception("Expected ' = ' after rule 'rule', got '@ = expr;' instead\n");






