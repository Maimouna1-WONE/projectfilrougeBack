<?php

namespace App\Controller;
use App\Entity\Formateur;
use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class FormateurController extends AbstractController
{
    private $service;
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    public function __construct(UserService $service,UserPasswordEncoderInterface $encoder,
                                SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, FormateurRepository $repo){
        $this->service=$service;
        $this->repo=$repo;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/formateurs",
     *     name="formateur_add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\FormateurController::addFormateur",
     *          "__api_resource_class"=Formateur::class,
     *          "__api_collection_operation_name"="add_formateur"
     *     }
     * )
     */
    public function addFormateur(Request $request,$entity="App\Entity\Formateur")
    {
        $this->service->addUser($request,$entity);
        return new JsonResponse("ajout reussie",Response::HTTP_CREATED,[],true);
    }


    /**
     * @Route(
     *     path="/api/formateurs/{id}",
     *     name="formateur_update",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\FormateurController::updateFormateur",
     *          "__api_resource_class"=Formateur::class,
     *          "__api_item_operation_name"="update_formateur"
     *     }
     * )
     */
    public function updateFormateur(Request $request, int $id)
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
