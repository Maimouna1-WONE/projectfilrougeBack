<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
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


class GroupeCompetenceController extends AbstractController
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
                                EntityManagerInterface $manager, GroupeCompetenceRepository $repo,
                                CompetenceRepository $repop,LivrablePartielRepository $repol){
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
     *     path="/api/admin/groupecompetences",
     *     name="postgrp",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::addgrp",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="postgrp"
     *     }
     * )
     */
   public function addgrp(Request $request){
       $grpcomp = json_decode($request->getContent(), true);
       //dd($grpcomp);
       foreach ($grpcomp["competence"] as $key => $value){
           $idComp = (int)$value;
           unset($value);
           $comp = $this->repop->find($idComp);
           $tab[] = $comp;
       }
       unset($grpcomp["competence"]);
       $grp = $this->serializer->denormalize($grpcomp, GroupeCompetence::class);
       $grp->setLibelle($grpcomp["libelle"]);
       $grp->setDescription($grpcomp["description"]);
       foreach ($tab as $val){
           $grp->addCompetence($val);
       }
       //dd($grp);
       $errors = $this->validator->validate($grp);
       if (count($errors)){
           $errors = $this->serializer->serialize($errors,"json");
           return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
       }
       $this->manager->persist($grp);
       $this->manager->flush();
       return $this->json("ajout reussi", Response::HTTP_CREATED);
   }
}
