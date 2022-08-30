<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
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
    #[Route('/api/users', name: 'users', methods: ['GET'])]
    public function getAllUsers(
        UserRepository $userRepository, 
        SerializerInterface $serializer,
        Request $request, 
        TagAwareCacheInterface $cachePool
        ): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getAllUsers-" . $page . "-" . $limit;
        $userList = $cachePool->get($idCache, function (ItemInterface $item) use ($userRepository, $page, $limit) {
            echo("L'element n'est pas encore en cache");
            $item->tag("usersCache");
            return $userRepository->findAllWithPagination($page, $limit);
        });
        $jsonUserList = $serializer->serialize($userList, 'json', ['groups' => 'listUsers']);
        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }
	
    
    #[Route('/api/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getDetailUser(User $user, SerializerInterface $serializer): JsonResponse {
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'showUser']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    
    #[Route('/api/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour supprimer un utilisateur')]
    public function deleteUser(
        User $user, 
        EntityManagerInterface $em,
        TagAwareCacheInterface $cachePool
        ): JsonResponse {
        $cachePool->invalidateTags(["UsersCache"]);//pr synchronisez le cache:pr forcer sa relecture on le supprime après tte opér° sur BD
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
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour créer un utilisateur')]
    public function createUser(
        Request $request, 
        SerializerInterface $serializer,
        EntityManagerInterface $em, 
        UrlGeneratorInterface $urlGenerator,
        TagAwareCacheInterface $cachePool
        ): JsonResponse {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->persist($user);

        $em->flush();
        $cachePool->invalidateTags(["UsersCache"]);//pr synchronisez le cache:pr forcer sa relecture on le supprime après tte opér° sur BD
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'listUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);	
    }
   
}

