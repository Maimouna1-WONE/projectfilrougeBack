<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
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
     *     name="user_add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::ajouter",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="add_user"
     *     }
     * )
     */
    public function ajouter(Request $request,$entity="App\Entity\Admin")
    {
        $this->service->addUser($request,$entity);
        return new JsonResponse("ajout reussie",Response::HTTP_CREATED,[],true);
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
     * @return JsonResponse
     */
    public function modifier(Request $request, int $id)
    {
        $object=$this->repo->find($id);
        $data = $this->service->UpdateUser($request, 'avatar');
        foreach ($data as $key=>$value){
            $ok="set".ucfirst($key);
            if ($key === "password"){
                $object->$ok($this->encoder->encodePassword($object,$value));
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

        return new JsonResponse("modification reussie",Response::HTTP_CREATED,[],true);
    }


}
