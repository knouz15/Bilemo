<?php

namespace App\Controller\V1;

use App\Entity\Phone;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
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
    
    #[OA\Response(
        response: 200,
        description: 'Retourne la liste des phones',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(ref: new Model(type: Phone::class, groups: ['listPhonesV1']))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Numéro de la page à afficher',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'phones')]
    #[Security(name: 'Bearer')]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        Request $request, 
        PaginatorInterface $paginator
    ): JsonResponse
    { 
        // // create a Response with an ETag and/or a Last-Modified header
        // $response = new Response();
        // $response->setEtag($phone->computeETag());
        // $response->setLastModified($phone->getPublishedAt());

        // // Set response as public. Otherwise it will be private by default.
        // $response->setPublic();

        // // Check that the Response is not modified for the given Request
        // if ($response->isNotModified($request)) {
        //     // return the 304 Response immediately
        //     return $response;
        // }


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
    #[OA\Response(
        response: 200,
        description: 'Retourne le détail d\'un phone',
        content: new Model(type: Phone::class, groups: ['showPhonesV1'])
    )]
    #[OA\Response(
        response: 404,
        description: 'Id introuvable'
    )]
    #[OA\Tag(name: 'phones')]
    #[Security(name: 'Bearer')]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer): JsonResponse {

        $jsonPhone = $serializer->serialize($phone, 'json', ['groups' => ['showPhoneV1']]);        
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
   }
}
