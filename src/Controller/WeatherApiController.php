<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\Response;
final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string    $city,
        WeatherUtil                            $util,
        #[MapQueryParameter('format')] string  $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndLocality($country, $city);

        if ($format === 'csv') {
            if($twig) {
                return $this->render('weather_api/index.csv.twig', [

                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,

                ]);
            }
                else{

                    $csv = "city,country,date,celsius,fahrenheit,precipitation,wind,humidity\n";
                    $csv .= implode(
                        "\n",
                        array_map(fn(Measurement $m) => sprintf(
                            '%s,%s,%s,%s,%s,%s,%s,%s',
                            $city,
                            $country,
                            $m->getDate()->format('Y-m-d'),
                            $m->getTemperature(),
                            $m->getFahrenheit(),
                            $m->getPrecipitation(),
                            $m->getWind(),
                            $m->getHumidity(),

                        ), $measurements)
                    );
                    return new Response($csv, 200, [
                        'Content-Type' => 'text/csv',
                    ]);
                }


        }
        if($twig){
            return $this->render('weather_api/index.json.twig', [

                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,

            ]);
        }
        else{
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Measurement $m) => [

                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getTemperature(),
                    'precipitation' => $m->getPrecipitation(),
                    'wind' => $m->getWind(),
                    'humidity' => $m->getHumidity(),

                ], $measurements),
            ]);

        }
        }
    }


