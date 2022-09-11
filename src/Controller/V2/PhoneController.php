<?php

namespace App\Controller\V2;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;;
use OpenApi\Annotations as OA;
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

    //pagination: https://127.0.0.1:8000/api/V2/phones?page=3
    // /**
    //  * List of the phones.
    //  *
    //  *
    //  * @OA\Response(
    //  *     response=200,
    //  *     description="Returns the list of the phones",
    //  *     @OA\JsonContent(
    //  *        type="array",
    //  *        @OA\Items(ref=@Model(type=App\Entity\Phone::class, groups={"listPhonesV2"}))
    //  *     )
    //  * )
    //  * @OA\Parameter(
    //  *     name="page",
    //  *     in="query",
    //  *     description="The field used to order phones",
    //  *     @OA\Schema(type="string")
    //  * )
    //  * @OA\Tag(name="Phones")
    //  * @NelmioSecurity(name="Bearer")
    //  */

    #[Route('/phones', name: 'phones', methods: ['GET'])]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        Request $request, 
        PaginatorInterface $paginator
    ): JsonResponse
    { 
        $donnees = $phoneRepository->findAll();
        $pagination = $paginator->paginate($donnees,$request->query->getInt('page',1),2);
        $response = 
        $this->json(
            // $phoneRepository->findAll(),
            $pagination,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => ['listPhonesV2']]);
        
        return $response;
    }




    #[Route('/phones/{id}', name: 'detailPhoneV2', methods: ['GET'])]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer): JsonResponse {

        $jsonPhone = $serializer->serialize($phone, 'json', ['groups' => ['showPhoneV2']]);        
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
   }
}
