<?php

function array2hash($array) {
    $hash = [];
    foreach ($array as $entry) {
        $name = $entry[0];
        $age = $entry[1];
        $hash[(int)$age] = $name;
    }
    return $hash;
}

?>
