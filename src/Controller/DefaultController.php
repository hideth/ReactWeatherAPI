<?php

namespace App\Controller;

use App\Entity\HistoryEntry;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DefaultController constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index(): Response
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
     * @Route("/api/weather/statistics", name="statistics")
     * @return JsonResponse
     */
    public function getStatisticsAction()
    {
        $historyEntryRepository = $this->entityManager->getRepository(HistoryEntry::class);
        $basicStats = $historyEntryRepository->getBasicStats();
        $mostSearchedCity = $historyEntryRepository->getMostSearchedCity();

        $response = [
            'MAX_TEMPERATURE' => $basicStats[0]['MAX_TEMPERATURE'],
            'MIN_TEMPERATURE' => $basicStats[0]['MIN_TEMPERATURE'],
            'AVG_TEMPERATURE' => $basicStats[0]['AVG_TEMPERATURE'],
            'COUNT' => $basicStats[0]['COUNT'],
            'MOST_SEARCHED_CITY' => $mostSearchedCity[0][0]['city'],
        ];

        return new JsonResponse($response);
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
     * @param HistoryEntry $historyEntry
     * @return mixed
     */
    public function serializeEntryData(HistoryEntry $historyEntry)
    {
        $historyEntry = $this->serializer->serialize($historyEntry, 'json');
        return $historyEntry;
    }
}
