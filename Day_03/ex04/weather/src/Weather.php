<?php
namespace Mtani\Weather;

use RestClient;

class Weather
{
    private $apiKey;
    private $city;
    private $weatherFile = 'weather.txt';

    public function __construct($apiKey, $city)
    {
        $this->apiKey = $apiKey;
        $this->city = $city;
    }

    public function fetchWeather()
    {
        $client = new RestClient([
            'base_url' => 'https://api.openweathermap.org/data/2.5/'
        ]);

        $response = $client->get('weather', [
            'q' => $this->city,
            'appid' => $this->apiKey,
            'units' => 'metric' // Celsius
        ]);

        if ($response->info->http_code !== 200) {
            throw new \Exception('Failed to fetch weather data');
        }

        $data = json_decode($response->response, true);

        $temp = $data['main']['temp'];
        $description = $data['weather'][0]['description'];

        $output = "Current weather in {$this->city}: {$temp}°C, {$description}\n";

        file_put_contents($this->weatherFile, $output);

        return $output;
    }
}
