<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Formateur;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    private $service;
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    public function __construct(UserService $service,UserPasswordEncoderInterface $encoder, SerializerInterface $serializer,ValidatorInterface $validator, EntityManagerInterface $manager, UserRepository $repo){
        $this->service=$service;
        $this->repo=$repo;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/admin/users",
     *     name="get",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getall",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="get"
     *     }
     * )
     */
    public function getall()
    {
        $userconnecte=($this->get("security.token_storage")->getToken())->getUser();
        //dd($userconnecte->getId());
        $user=$this->repo->trouver((int)$userconnecte->getId());
        //dd($user);
        return $this->json($user,Response::HTTP_OK, [] ,['groups' => ['user:read']]);
    }

    /**
     * @Route(
     *     path="/api/admin/users",
     *     name="user_add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::ajouter",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="add_user"
     *     }
     * )
     */
    public function ajouter(Request $request)
    {
        $val = $this->service->addUser($request);
        $status = Response::HTTP_BAD_REQUEST;
        if ($val instanceof User){
            $status =Response::HTTP_CREATED;
        }
        return $this->json("Ajout reussi",$status);
    }

    /**
     * @Route(
     *     path="/api/admin/users/{id}",
     *     name="user_update",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::modifier",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="update_user"
     *     }
     * )
     * @param Request $request
     * @param int $id
     */
    public function modifier(Request $request, int $id)
    {
        $object = $this->repo->find($id);
        $data = $this->service->UpdateUser($request, 'avatar');
        foreach ($data as $key=>$value){
            $ok="set".ucfirst($key);
            if ($key === "password"){
                //dd((string)substr($data[$key],0,-6));
                $object->$ok($this->encoder->encodePassword($object,(string)substr($data[$key],0,-6)));
            }
            else {
                $object->$ok($value);
            }
        }
        $errors = $this->validator->validate($object);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($object);
        $this->manager->flush();
        //dd($object);
        return $this->json("modification reussie",201);
    }
    /**
     * @Route(
     *     path="/api/admin/users/search",
     *     name="search",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::search",
     *          "__api_resource_class"=User::class,
     *          "__api_item_operation_name"="search"
     *     }
     * )
     */
    public function search(){
        $user=($this->get("security.token_storage")->getToken())->getUser();
        return $this->json($user,Response::HTTP_OK, [] ,['groups' => ['getuserconnecte:read']]);
    }

}
