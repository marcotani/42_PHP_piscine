<?php

function array2hash_sorted($array) {
    $hash = [];
    foreach ($array as $entry) {
        $name = $entry[0];
        $age = $entry[1];
        $hash[$name] = (int)$age;
    }
    krsort($hash);
    return $hash;
}

?>