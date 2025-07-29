<?php

require_once 'Text.php';

class TemplateEngine {
    public function createFile($fileName, Text $text) {

        $template = "<!DOCTYPE html>\n<html>\n<head>\n<title>Text</title>\n</head>\n<body>\n";

        $template .= implode("\n", $text->htmlify());

        $template .= "\n</body>\n</html>";

        file_put_contents($fileName, $template);
    }
}

?>