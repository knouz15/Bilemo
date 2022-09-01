<?php

namespace App\Controller\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    //avec pagination: https://127.0.0.1:8000/api/userss?page=3&limit=2
    //le cache: 
    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getAllUsers(
        UserRepository $userRepository, 
        // SerializerInterface $serializer,
        Request $request, 
        PaginatorInterface $paginator
        // TagAwareCacheInterface $cachePool
        ): JsonResponse
    {
        // $page = $request->get('page', 1);
        // $limit = $request->get('limit', 3);

        // $idCache = "getAllUsers-" . $page . "-" . $limit;
        // $userList = $cachePool->get($idCache, function (ItemInterface $item) use ($userRepository, $page, $limit) {
        //     echo("L'element n'est pas encore en cache");
        //     $item->tag("usersCache");
        //     return $userRepository->findAllWithPagination($page, $limit);
        // });
        // $jsonUserList = $serializer->serialize($userList, 'json', ['groups' => 'listUsers']);
        // return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
        $donnees = $userRepository->findAll();
        $pagination = $paginator->paginate($donnees,$request->query->getInt('page',1),5);
        $response = 
        $this->json(
            // $userRepository->findAll(),
            $pagination,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            ['groups' => 'listUsers']);
        
        return $response;

    }
    
    #[Route('/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getDetailUser(
        User $user, 
        SerializerInterface $serializer
        ): JsonResponse {
        $this->denyAccessUnlessGranted('view', $user);
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'showUser']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    
    #[Route('/api/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    // #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour supprimer un utilisateur')]
    public function deleteUser(
        User $user, 
        EntityManagerInterface $em
        ): JsonResponse {
        $this->denyAccessUnlessGranted('delete', $user);
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /** 
     * Exemple de données :
     * {
     *     "lastName": "Jolie",
     *     "username": "J.R.R"
     * }
     */
    #[Route('/api/users', name: 'createUser', methods: ['POST'])]
    // #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour créer un utilisateur')]
    public function createUser(
        Request $request, 
        SerializerInterface $serializer,
        EntityManagerInterface $em, 
        UrlGeneratorInterface $urlGenerator
        ): JsonResponse {
        // $this->denyAccessUnlessGranted('create', $user);
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->persist($user);

        $em->flush();
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'listUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);//on calcule l'url sur laquelle on teste si l'elment a vraiment été créé
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);//et on retrouve cette url sous le nom lacation ds le header de la réponse de la création	
    }
   
}

