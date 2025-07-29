<?php

class TemplateEngine {
    public function createFile($fileName, $templateName, $parameters) {

        if (!file_exists($templateName)) {
            throw new Exception("Template file not found: $templateName");
        }

        $template = file_get_contents($templateName);

        foreach ($parameters as $key => $value) {
            echo "Replacing {$key} with {$value}\n";
            $template = str_replace("{" . $key . "}", $value, $template);
        }

        file_put_contents($fileName, $template);
    }
}

?>