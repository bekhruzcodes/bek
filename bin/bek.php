#!/usr/bin/env php
<?php

// Attempt to find the Composer autoloader
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../../../autoload.php',
    getcwd() . '/vendor/autoload.php'
];

$autoloadFound = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require $path;
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    echo "Error: Autoload file not found. Make sure you've run composer install.\n";
    exit(1);
}

// Delegate to the actual implementation
require __DIR__ . '/bek';