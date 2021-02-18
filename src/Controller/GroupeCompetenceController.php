<?php

namespace App\Controller;
use App\Entity\Competence;
use App\Entity\Niveau;
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
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
   public function addgrp(Request $request){
       $grpcomp = json_decode($request->getContent(), true);
       //dd($grpcomp);
       $grp = new GroupeCompetence();
       //dd($grp);
       $grp->setLibelle($grpcomp["libelle"]);
       $grp->setDescription($grpcomp["description"]);
       //dd($grp);
       foreach ($grpcomp["competence"] as $key => $value){
           if(is_numeric($value)){
               $idComp = (int)$value;
               unset($value);
               $comp = $this->repop->find($idComp);
               $this->manager->persist($comp);
               //dd($comp);
           }
           else{
               //dd($value);
               $comp = $this->repop->find($value['id']);
               /*$comp = new Competence();
               $comp->setLibelle($value['libelle']);
               $comp->setDescription($value['description']);
               foreach ($value['niveau'] as $k => $v){
                   //dd($v);
                   $niv = new Niveau();
                   $niv->setLibelle($v['libelle']);
                   $niv->setCritereEvaluation($v['critereEvaluation']);
                   $niv->setAction($v['action']);
                   $comp->addNiveau($niv);
                   $this->manager->persist($niv);
               }
               //dd($comp);
               if ($value['groupeCompetences'] !== []){
                   foreach ($value['groupeCompetences'] as $g => $grpc){
                       $idgrpComp = (int)$grpc;
                       unset($grpc);
                       $grpcomp = $this->repo->find($idgrpComp);
                       $comp->addGroupeCompetence($grpcomp);
                   }
               }
               $this->manager->persist($comp);*/
           }
           $grp->addCompetence($comp);
       }
       //dd($grp->getCompetence());
       $errors = $this->validator->validate($grp);
       if (count($errors)){
           $errors = $this->serializer->serialize($errors,"json");
           return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
       }
       $this->manager->persist($grp);
       $this->manager->flush();
       return $this->json("ajout reussi", Response::HTTP_CREATED);
   }

    /**
     * @Route(
     *     path="/api/admin/groupecompetences/{id}",
     *     name="putgrp",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::putgrp",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_item_operation_name"="putgrp"
     *     }
     * )
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function putgrp(int $id, Request $request){
        $object = $this->repo->find($id);
        $grpcomp = $request->request->all();
        //dd($grpcomp);
        //$grpcomp=json_decode($request->getContent(), true);
        $compobj = $object->getCompetence();
        if($grpcomp['competence']) {
            $tab =$grpcomp['competence'];
            foreach ($tab as $value) {
                $c = (integer)$value;
                unset($value);
                $comp = $this->repop->find($c);
                $tabokk[] = $comp;
            }
        }
        foreach ($grpcomp as $key=>$value){
            if ($key !== "competence"){
                $ok = "set" . ucfirst($key);
                $object->$ok($value);
            }
        }
        foreach ($tabokk as $keyok=>$valueok){
            foreach ($compobj as $k => $v){
                if (in_array($v, $tabokk, true) === false){
                    $object->removeCompetence($v);
                }
                $object->addCompetence($valueok);
            }
        }
        $errors = $this->validator->validate($object);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($object);
        $this->manager->flush();
        //dd($object->getCompetence());
        return $this->json('modification reussie', Response::HTTP_OK);
    }
}
