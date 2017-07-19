<?php

spl_autoload_register(
    function ($class) {
        $namespacePrefix = '{{ projectName }}\\';
        $namespacePrefixLength = strlen($namespacePrefix);

        if (strncmp($class, $namespacePrefix, $namespacePrefixLength) !== 0) {
            return;
        }

        $relativeClassName = substr($class, $namespacePrefixLength);
        // __DIR__ will give '~/.../output/'
        $filePath = __DIR__ . '/{{ projectName }}/' . strtr($relativeClassName, '\\', '/') . '.php';

        if (is_file($filePath)) {
            include $filePath;
        }
    }
);
