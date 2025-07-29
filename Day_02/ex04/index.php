<?php

require_once 'TemplateEngine.php';
require_once 'Elem.php';

try {
    //$test = new Elem('test', 'This is a test element');
    $elem = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'The Beautiful Test Page'));
    $head->pushElement(new Elem('meta', [], ['charset' => 'UTF-8']));
    $elem->pushElement($head);
    $body = new Elem('body');
    $body->pushElement(new Elem('h1', 'Hello World'));
    $body->pushElement(new Elem('p', 'Lorem ipsum'));
    $body->pushElement(new Elem('p', 'Welcome!', ['style' => 'color: red']));
    $body->pushElement(new Elem('div', 'This is a div element'));
    $body->pushElement(new Elem('span', 'This is a span element'));
    $body->pushElement(new Elem('br'));
    $body->pushElement(new Elem('img', [], ['src' => 'https://picsum.photos/200', 'alt' => 'Example Image']));
    $body->pushElement(new Elem('br'));
    $body->pushElement(new Elem('hr'));
    $body->pushElement(new Elem('ul'));
    $body->pushElement(new Elem('li', 'Item 1'));
    $body->pushElement(new Elem('li', 'Item 2'));
    $body->pushElement(new Elem('li', 'Item 3', ['style' => 'color: blue']));
    $elem->pushElement($body);

    $engine = new TemplateEngine($elem);
    $engine->createFile('index.html');
    if (!class_exists('Elem')) {
        throw new Exception('Elem class not found.');
    }
} catch (MyException $e) {
    echo "Error: " . $e->getErrorMessage();
    exit;
}

?>