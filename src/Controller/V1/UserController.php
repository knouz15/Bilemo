<?php

namespace App\Controller\V1;

use App\Entity\User;
use App\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    //avec pagination: https://127.0.0.1:8000/api/V1/users?page=1
    /**
     * List of the users.
     *
     * This call takes into account all customer's users.
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of the users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class, groups={"listUsers"}))
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
     *     description="Pagination to order users",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="Users")
     * @NelmioSecurity(name="Bearer")
     */
    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getAllUsers(
        UserRepository $userRepository, 
        Request $request, 
        PaginatorInterface $paginator,
        SerializerInterface $serializer
        ): JsonResponse
    {
        $donnees = $userRepository->findBy(['customer'=>$this->getUser()]);
        $page = $request->query->getInt("page", 1);
        $pagination = $paginator->paginate($donnees,$page,5);
        $response = new JsonResponse();
        $response->setEtag(md5(serialize($pagination)));
        $response->setPublic();
        $response->isNotModified($request);
        
        $jsonPhones = $serializer->serialize($pagination,'json', ['groups' => ['listUsers']]);
            
        $response->setContent($jsonPhones);        
        return $response;
    }
    
    /**
     * Detail of an user.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of the user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class, groups={"showUser"}))
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Id not found",
     *     
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="Invalid Credentials or invalid Token",
     *     
     * )
     * @OA\Tag(name="Users")
     * @NelmioSecurity(name="Bearer")
     */
    #[Route('/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getDetailUser(
        User $user, 
        Request $request,
        SerializerInterface $serializer
        ): JsonResponse {
        $response = new JsonResponse();
        $response->setEtag(md5(serialize($user)));
        $response->setLastModified($user->getUpdatedAt());
        $response->setPublic();
        if ($response->isNotModified($request)) {
            return $response;
        }
        $this->denyAccessUnlessGranted('view', $user, "Denied! Cet utilisateur ne fait pas partie des votres.");
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'showUser']);
        $response->setContent($jsonUser);
         return $response;
    }

    /**
     * Delete an user.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Delete the user",
     * )
     * @OA\Response(
     *     response=404,
     *     description="Id not found",
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="Invalid Credentials or invalid Token",
     *     
     * )
     * @OA\Tag(name="Users")
     * @NelmioSecurity(name="Bearer")
     */
    #[Route('/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits pour supprimer un utilisateur')]
    public function deleteUser(
        User $user, 
        EntityManagerInterface $em
        ): JsonResponse {
        $this->denyAccessUnlessGranted('delete', $user, "Denied! Cet utilisateur ne fait pas partie des votres.");
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // Exemple de données :
    // {
    //   "email":"toto@y.fr",
    //   "lastName": "Jolie",
    //   "firstname": "Laure",
    //   "adress": "1 rue belleville",
    //   "zipcode": "70000",
    //   "city": "Ouest",
    //   "country": "OZ"
    //  }
    // 
    /**
     * Create an user.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the created user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class, groups={"listUsers"}))
     *     )
     * )
     *  @OA\Response(
     *     response=400,
     *     description="Invalid form inputs",
     * )
     * *  * @OA\Response(
     *     response=403,
     *     description="Invalid Credentials or invalid Token",
     *     
     * )
     * @OA\Tag(name="Users")
     * @NelmioSecurity(name="Bearer")
     */
    #[Route('/users', name: 'createUser', methods: ['POST'])]
    public function createUser(
        Security $security,
        Request $request, 
        SerializerInterface $serializer,
        EntityManagerInterface $em, 
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator,
        ): JsonResponse {
            
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user, null, ['user']);
        $user->setCustomer($security->getUser());
        $em->persist($user);
        $em->flush();

        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new JsonResponse($errors, 500, [
                'Content-Type' => 'application/json'
            ],true);
        }

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'listUsers']);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);//on calcule l'url sur laquelle on teste si l'elment a vraiment été créé
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);//et on retrouve cette url sous le nom location ds le header de la réponse de la création	
    }
   
}
