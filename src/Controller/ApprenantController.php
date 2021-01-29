<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\ProfilRepository;
use App\Repository\PromoRepository;
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


class ApprenantController extends AbstractController
{
    private $service;
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repol;
    private $repop;
    public function __construct(UserService $service,UserPasswordEncoderInterface $encoder,
                                SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, ApprenantRepository $repo,
                                PromoRepository $repop,LivrablePartielRepository $repol){
        $this->service=$service;
        $this->repo=$repo;
        $this->repol=$repol;
        $this->repop=$repop;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
     * @Route(
     *     path="/api/apprenants",
     *     name="apprenant_add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::addApprenant",
     *          "__api_resource_class"=Apprenant::class,
     *          "__api_collection_operation_name"="add_apprenant"
     *     }
     * )
     */
    public function addApprenant(Request $request,$entity="App\Entity\Apprenant")
    {
        $this->service->addUser($request,$entity);
        return new JsonResponse("ajout reussie",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}",
     *     name="apprenant_update",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::updateApprenant",
     *          "__api_resource_class"=Apprenant::class,
     *          "__api_item_operation_name"="update_apprenant"
     *     }
     * )
     */
    public function updateApprenant(Request $request, int $id)
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
        $object->setIsConnected(1);
        $this->manager->persist($object);
        $this->manager->flush();

        return new JsonResponse("modification reussie",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/brief/{id1}/assignation",
     *     name="assigne",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::assigner",
     *          "__api_resource_class"=Apprenant::class,
     *          "__api_item_operation_name"="assigne"
     *     }
     * )
     */
    public function assigner(int $id)
    {
        $ok=$this->repop->find($id);
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}/livrablepartiels/{id1}",
     *     name="livpartiel",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ApprenantController::Updatelivpartiel",
     *          "__api_resource_class"=Apprenant::class,
     *          "__api_item_operation_name"="livpartiel"
     *     }
     * )
     */
    public function Updatelivpartiel(int $id1)
    {
        $app=$this->repol->find($id1);
        $app->getApprenantLivrablePartiel()->setEtat(1);
        $this->manager->persist($app);
        $this->manager->flush();
        return new JsonResponse("statut livrable modifié",Response::HTTP_CREATED,[],true);
    }
}
