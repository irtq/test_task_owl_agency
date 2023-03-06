<?php

namespace App\Controller;

use App\Entity\FormData;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{
    public function test(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $arr = json_decode('{"name":"Aasasdaasd","email":"ivan@yandex.ru","text":null}');

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


        return $this->json($arr);
    }
}
