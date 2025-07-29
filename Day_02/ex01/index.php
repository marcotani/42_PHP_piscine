<?php

require_once 'TemplateEngine.php';

$strings = [
    'Hello, my baby!',
    'Hello, my honey!',
    'Hello, my ragtime gal!',
    'Send me a kiss by wire.',
    'Baby, my heart\'s on fire!',
    'If you refuse me,',
    'Honey, you loose me.',
    'Then you\'ll be left alone.',
    'Oh baby, telephone',
    'And tell me I\'m',
    'Your own!',
];

$text = new Text($strings);

$engine = new TemplateEngine();

try {
    $engine->createFile('textTest.html', $text);
    echo "File has been created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}