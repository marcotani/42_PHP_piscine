<?php
require 'vendor/autoload.php';

use Mtani\Weather\Weather;

$apiKey = 'b78af0c465599f86ea360fedd57a1a83';
$city = 'Florence';

$weather = new Weather($apiKey, $city);

try {
    echo $weather->fetchWeather();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
