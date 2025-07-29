<?php

class Text {
    private $strings = [];

    function __construct(array $strings) {
        $this->strings = $strings;
    }

    function addString($string) {
        $this->strings[] = $string;
    }

    public function htmlify() {
        
        $html = [];

        foreach ($this->strings as $s) {
            $html[] = "<p>" . htmlspecialchars($s) . "</p>";
        }

        return $html;
    }
}

?>