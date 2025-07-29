<?php

require_once 'TemplateEngine.php';
require_once 'HotBeverage.php';
require_once 'Tea.php';
require_once 'Coffee.php';

$tea = new Tea();
$coffee = new Coffee();

$engine = new TemplateEngine();

try {
    $engine->createFile($tea);
    echo "A piping hot Tea is ready!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $engine->createFile($coffee);
    echo "A coffee blacker than night is ready to be enjoyed.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}