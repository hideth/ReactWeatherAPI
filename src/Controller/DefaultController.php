<?php

namespace App\Controller;

use App\Entity\HistoryEntry;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DefaultController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * DefaultController constructor.
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/api/weather/add", name="add_entry")
     * @param HttpFoundationRequest $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function newEntryAction(HttpFoundationRequest $request)
    {
        $data = json_decode($request->getContent(), true);
        $weatherResponse = $this->getWeatherResponse($data);
        $historyEntry = $this->generateNewEntry($weatherResponse);
        $historyResponse = $this->serializeEntryData($historyEntry);
        $response = new JsonResponse();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent($historyResponse);

        return $response;
    }

    /**
     * @Route("/api/users", name="users")
     * @return JsonResponse
     */
    public function getUsers()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Olususi Oluyemi',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
                'imageURL' => 'https://randomuser.me/api/portraits/women/50.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Camila Terry',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
                'imageURL' => 'https://randomuser.me/api/portraits/men/42.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Joel Williamson',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
                'imageURL' => 'https://randomuser.me/api/portraits/women/67.jpg'
            ],
            [
                'id' => 4,
                'name' => 'Deann Payne',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
                'imageURL' => 'https://randomuser.me/api/portraits/women/50.jpg'
            ],
            [
                'id' => 5,
                'name' => 'Donald Perkins',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
                'imageURL' => 'https://randomuser.me/api/portraits/men/89.jpg'
            ]
        ];

        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent(json_encode($users));

        return $response;
    }

    /**
     * @param $data
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getWeatherResponse($data)
    {
        $httpClient = HttpClient::create();
        $apiKey = '0131cb1f0a0575cb2a015509d6244e4c';
        $lat = $data['lat'];
        $lng = $data['lng'];
        $weatherRequest = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/find?lat=' . $lat . '&lon=' . $lng . '&appid=' . $apiKey . '');
        return json_decode($weatherRequest->getContent())->list[0];
    }

    /**
     * @param $weatherResponse
     * @return HistoryEntry
     */
    public function generateNewEntry($weatherResponse): HistoryEntry
    {
        $historyEntry = new HistoryEntry();
        $historyEntry
            ->setCreatedAt(new \DateTime())
            ->setCloud($weatherResponse->wind->speed)
            ->setCity($weatherResponse->name)
            ->setDescription($weatherResponse->weather[0]->description)
            ->setLatitude($weatherResponse->coord->lat)
            ->setLongitude($weatherResponse->coord->lon)
            ->setTemperature($weatherResponse->main->temp)
            ->setWind($weatherResponse->wind->speed);

        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->persist($historyEntry);
        $objectManager->flush();
        return $historyEntry;
    }

    /**
     * @param $serializer
     * @param HistoryEntry $historyEntry
     * @return mixed
     */
    public function serializeEntryData(HistoryEntry $historyEntry)
    {
        $historyEntry = $this->serializer->serialize($historyEntry, 'json');
        return $historyEntry;
    }
}
