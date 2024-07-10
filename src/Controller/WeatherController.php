<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class WeatherController extends AbstractController
{
    // #[Route('/lucky/number')]
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function index(Request $request): Response
    {
        $city = $request->request->get('name');
        if ($city == null)$city = 'New York';

        $apiKey = '3C8TRCWYPKSPU83H6U8CJ5CUR';
       
        $url = sprintf('https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/%s/?unitGroup=us&key=%s', $city, $apiKey);
        
        $response = $this->httpClient->request('GET', $url);
        $weatherData = $response->toArray();
        $days = [];
        foreach ($weatherData['days'] as $day) {
            $days[] = [
                'datetime' => $day['datetime'],
                'temp' => $day['temp'],
                'tempmax' => $day['tempmax'],
                'tempmin' => $day['tempmin'],
                'dew' => $day['dew'],
                'humidity' => $day['humidity'],
                'precip' => $day['precip'],
                'windgust' => $day['windgust'],
                'windspeed' => $day['windspeed'],
                'winddir' => $day['winddir'],
                'description' => $day['description'],
                'pressure' => $day['pressure'],
                'cloudcover' => $day['cloudcover'],
                'visibility' => $day['visibility'],
                'icon' => $day['icon'],
            ];
        }
        return $this->render('weather/index.html.twig', [
            'weatherData' => $weatherData,
            'location' => $city
        ]);
    }
}
