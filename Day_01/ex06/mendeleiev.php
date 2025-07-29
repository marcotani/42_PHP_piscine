<?php

$input_file = 'ex06.txt';
$output_file = 'mendeleiev.html';

if (!file_exists($input_file)) {
    die("Input file '$input_file' does not exist.\n");
}

$lines = file($input_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$elements = [];

foreach ($lines as $line) {
    list($name, $data_str) = explode(' = ', $line);
    $data_parts = explode(',', $data_str);
    $element = ['name' => $name];

    foreach ($data_parts as $part) {
        if (strpos($part, ':') !== false) {
            list($key, $value) = explode(':', $part, 2);
            $element[trim($key)] = trim($value);
        }
    }

    $elements[] = $element;
}

$html = "<!DOCTYPE html>\n<html>\n<head>\n<title>Mendeleiev Table</title>\n<style>\nbody {\nbackground-color: black;\ncolor: white;\n}\n</style>\n</head>\n<body>\n<table>\n<tr>\n";

$current_col = 0;

foreach ($elements as $el) {
    $pos = (int)$el['position'];

    while ($current_col < $pos) {
        $html .= "<td></td>\n";
        $current_col++;
    }

    $name = $el['name'];
    $number = $el['number'];
    $symbol = $el['small'];
    $molar = $el['molar'];
    $electron = trim($el['electron']);

    $html .= "<td style=\"border: 1px solid white; padding:10px\">\n";
    $html .= "<h4>$name</h4>\n";
    $html .= "<ul>\n";
    $html .= "<li>No $number</li>\n";
    $html .= "<li>$symbol</li>\n";
    $html .= "<li> $molar </li>\n";
    $html .= "<li>$electron electron</li>\n";
    $html .= "</ul>\n</td>\n";

    $current_col++;

    if ($current_col >= 18) {
        $html .= "</tr>\n<tr>\n";
        $current_col = 0;
    }
}

$html .= "</tr>\n</table>\n</body>\n</html>";

file_put_contents($output_file, $html);

?>
