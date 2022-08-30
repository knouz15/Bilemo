<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{ 

    //avec pagination
    //https://127.0.0.1:8000/api/userss?page=3&limit=2
    #[Route('/api/phones', name: 'phones', methods: ['GET'])]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        SerializerInterface $serializer,
        Request $request, 
        TagAwareCacheInterface $cachePool
    ): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getAllPhones-" . $page . "-" . $limit;
        $phoneList = $cachePool->get($idCache, function (ItemInterface $item) use ($phoneRepository, $page, $limit) {
            echo("L'element n'est pas encore en cache");
            $item->tag("usersCache");
        
            return $phoneRepository->findAllWithPagination($page, $limit);
        });
        
        $jsonPhoneList = $serializer->serialize($phoneList, 'json', ['groups' => 'listPhones']);
        return new JsonResponse($jsonPhoneList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer): JsonResponse {

        $jsonPhone = $serializer->serialize($phone, 'json', ['groups' => 'showPhone']);        
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
   }
}
