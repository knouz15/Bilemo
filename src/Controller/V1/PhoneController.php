<?php

namespace App\Controller\V1;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerTk6IcmD\PaginatorInterface_82dac15;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{ 

    //pagination : https://127.0.0.1:8000/api/users?page=3
    #[Route('/phones', name: 'phones', methods: ['GET'])]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        // SerializerInterface $serializer,
        Request $request, 
        PaginatorInterface $paginator
        // TagAwareCacheInterface $cachePool
    ): JsonResponse
    { 
        $donnees = $phoneRepository->findAll();
        $pagination = $paginator->paginate($donnees,$request->query->getInt('page',1),5);
        $response = 
        $this->json(
            // $phoneRepository->findAll(),
            $pagination,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => ['listPhonesV1']]);
        
        return $response;
    }


    #[Route('/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer): JsonResponse {

        $jsonPhone = $serializer->serialize($phone, 'json', ['groups' => ['showPhoneV1']]);        
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
   }
}
