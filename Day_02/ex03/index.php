<?php

require_once 'TemplateEngine.php';
require_once 'Elem.php';

try {
    //$test = new Elem('test', 'This is a test element');
    $elem = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'The Beautiful Test Page'));
    $head->pushElement(new Elem('meta', ['charset' => 'UTF-8']));
    $elem->pushElement($head);
    $body = new Elem('body');
    $body->pushElement(new Elem('h1', 'Hello World'));
    $body->pushElement(new Elem('p', 'Lorem ipsum'));
    $body->pushElement(new Elem('div', 'This is a div element'));
    $body->pushElement(new Elem('span', 'This is a span element'));
    $body->pushElement(new Elem('br'));
    $body->pushElement(new Elem('hr'));
    $elem->pushElement($body);

    $engine = new TemplateEngine($elem);
    $engine->createFile('index.html');
    if (!class_exists('Elem')) {
        throw new Exception('Elem class not found.');
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}

$engine = new TemplateEngine($elem);
$engine->createFile('index.html');

?>