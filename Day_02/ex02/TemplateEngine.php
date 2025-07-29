<?php

class TemplateEngine {
    public function createFile(HotBeverage $drink) {

        $template = file_get_contents("template.html");

        $reflection = new ReflectionClass($drink);
        $properties = $reflection->getProperties();

        $parameters = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $parameters[$property->getName()] = $property->getValue($drink);
        }

        foreach ($parameters as $key => $value) {
            $template = str_replace("{" . $key . "}", $value, $template);
        }

        $classname = get_class($drink);
        $fileName = strtolower($classname) . '.html';

        file_put_contents($fileName, $template);
    }
}

?>