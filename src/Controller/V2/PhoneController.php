<?php

namespace App\Controller\V2;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerTk6IcmD\PaginatorInterface_82dac15;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{  

    //pagination: https://127.0.0.1:8000/api/V2/phones?page=3
    /**
     * List of the phones.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of the phones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\Phone::class, groups={"listPhonesV2"}))
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="Invalid Credentials or invalid Token",
     *     
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Pagination to order phones",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="Phones")
     * @NelmioSecurity(name="Bearer")
     
     */

    #[Route('/phones', name: 'phonesV2', methods: ['GET'])]
    public function getAllPhones(
        PhoneRepository $phoneRepository,
        Request $request, 
        PaginatorInterface $paginator,
        SerializerInterface $serializer
    ): JsonResponse
    {   
        $donnees = $phoneRepository->findAll();
        $pagination = $paginator->paginate($donnees,$request->query->getInt('page',1),2);
        $response = new JsonResponse();
        $response->setEtag(md5(serialize($pagination)));
        $response->setPublic();
        $response->isNotModified($request);
        
        $jsonPhones = 
            $serializer->serialize($pagination,'json', ['groups' => ['listPhonesV1']]);
            
            $response->setContent($jsonPhones);
        // $response = $this->json(
        //     $pagination,
        //     Response::HTTP_OK,
        //     ['Content-Type' => 'application/json'],
        //     ['groups' => ['listPhonesV2']]);
        
        return $response;
    }

/**
     * Detail of an phone.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of the phone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\Phone::class, groups={"showPhoneV2"}))
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Id not found",
     *     
     * )
     * 
     *  * @OA\Response(
     *     response=403,
     *     description="Invalid Credentials or invalid Token",
     *     
     * )
     * @OA\Tag(name="Phones")
     * @NelmioSecurity(name="Bearer")
     *
     * @param Phones $phone
     * @return Response
     */
    #[Route('/phones/{id}', name: 'detailPhoneV2', methods: ['GET'])]
    public function getDetailPhone(Phone $phone, SerializerInterface $serializer,Request $request): JsonResponse {
        $response = new JsonResponse();
        $response->setEtag(md5(serialize($phone)));
        $response->setLastModified($phone->getUpdatedAt());
        $response->setPublic();
            if ($response->isNotModified($request)) {
            return $response;
            
        }
        
        $jsonPhone = $serializer->serialize($phone, 'json', ['groups' => ['showPhoneV2']]);     
        $response->setContent($jsonPhone);   
        return $response;
   }
}
