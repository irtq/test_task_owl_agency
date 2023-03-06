<?php

namespace App\Controller;

use App\Entity\FormData;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    private function getDataFromFrontApi(): string
    {
        $response = $this->client->request(
            'GET',
            'http://'.$_SERVER['HTTP_HOST'].'/api/front_apis/1'
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            throw new Exception('API status code is not 200');
        }
        return $response->getContent();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testTask(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $arr = json_decode($this->getDataFromFrontApi());

        $data = new FormData();
        $data->setName($arr->name);
        $data->setEmail($arr->email);
        $data->setMessageText($arr->text);



        $errors = $validator->validate($data);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $entityManager->persist($data);
        $entityManager->flush();
        echo ("Saved new form data with id ".$data->getId()."\n");
        return $this->json($arr);
    }
}
