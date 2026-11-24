<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    public function __construct(
        private readonly WeatherService $weather
    ) {}

    #[Route('/weather', name: 'app_weather')]
    public function index(): Response
    {
        $weather = $this->weather->getWeather() ?? [];
        return $this->render('weather/index.html.twig', [
            'weather' => $weather,
        ]);
    }
}
