<?php

namespace App\Controller;
use App\Entity\Brief;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
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


class BriefController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repop;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, BriefRepository $repo,PromoRepository $repop){
        $this->repo=$repo;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
    * @Route(
    *     path="/api/formateurs/briefs",
    *     name="postbrief",
    *     methods={"POST"},
    *     defaults={
    *          "__controller"="App\Controller\BriefController::addBrief",
    *          "__api_resource_class"=Brief::class,
    *          "__api_collection_operation_name"="postbrief"
    *     }
    * )
    */
    public function addBrief(Request $request)
    {
        //$brief = $request->request->all();
        $brief=json_decode($request->getContent(), true);
        $avatar = $request->files->get("avatar");
        if ($avatar){
        $avatar = fopen($avatar->getRealPath(),"rb");
        $brief["avatar"] = $avatar;
        }
        $brief = $this->serializer->denormalize($brief,"App\Entity\Brief");
        $brief->setDateCreation(new \DateTime);
        $formateur=($this->get("security.token_storage")->getToken())->getUser();
        $brief->setFormateur($formateur);
        $errors = $this->validator->validate($brief);
        if (count($errors)){
        $errors = $this->serializer->serialize($errors,"json");
        return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($brief);
        $this->manager->flush();
        if ($avatar){
        fclose($avatar);
        }
        return $this->json($this->serializer->normalize($brief),Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/formateurs/briefs/{id}",
     *     name="duplique",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::dupliqueBrief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="duplique"
     *     }
     * )
     */
    public function dupliqueBrief(int $id)
    {
        $duplique=$this->repo->find($id);
        $plus=clone $duplique;
        $this->manager->persist($plus);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($plus), Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/brief/{id1}",
     *     name="putbr",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::cloturer",
     *          "__api_resource_class"=Brief::class,
     *          "__api_item_operation_name"="putbr"
     *     }
     * )
     */
    public function cloturer(int $id1)
    {
        //$brief=$this->repo->find($id1);
        //$brief->setStatut("brouillon");
        //$this->manager->persist($brief);
        //$this->manager->flush();
        //return $this->json("Brief cloturé", Response::HTTP_CREATED);
    }


}