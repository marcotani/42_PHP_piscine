<?php

class TemplateEngine {

    private $element;

    function __construct(Elem $element) {
        $this->element = $element;
    }

    function createFile($filename) {
        $htmlContent = $this->element->getHTML();
        file_put_contents($filename, $htmlContent);
    }
}

?>