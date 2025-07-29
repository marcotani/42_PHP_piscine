<?php

require_once 'TemplateEngine.php';

$engine = new TemplateEngine();

$parameters = [
    'nom' => 'The Hitchhiker\'s Guide to the Galaxy',
    'auteur' => 'Douglas Adams',
    'description' => 'A comedic science fiction series that follows the adventures of Arthur Dent and his alien friend Ford Prefect as they travel through space after Earth is destroyed.',
    'prix' => '12.49'
];

try {
    $engine->createFile('book1.html', 'book_description.html', $parameters);
    echo "File has been created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}