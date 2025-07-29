<?php

$myfile = file_get_contents("ex01.txt");

$words = explode(",", $myfile);

foreach ($words as $word) {
    $word = trim($word);
    if (strlen($word) > 0) {
        echo $word . "\n";
    }
}

?>