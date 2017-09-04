<?php

spl_autoload_register(
    function ($class) {
        $namespacePrefix = 'ParserGenerator\\';
        $namespacePrefixLength = strlen($namespacePrefix);

        if (strncmp($class, $namespacePrefix, $namespacePrefixLength) !== 0) {
            return;
        }

        $relativeClassName = substr($class, $namespacePrefixLength);
        $filePath = dirname(dirname(__DIR__)) . '/projects/parser-generator/src/' . strtr($relativeClassName, '\\', '/') . '.php';
        //echo "Filepath:".$filePath."\n";
        if (is_file($filePath)) {
            include $filePath;
        }
    }
);
