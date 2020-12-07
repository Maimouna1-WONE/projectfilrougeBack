<?php

namespace App\Controller;
use App\Entity\LivrableAttendu;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\CompetenceValideeRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\LivrableAttenduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Chat;


class LivrableAttenduController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoapp;
    private $repogrp;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, LivrableAttenduRepository $repo,
                                ApprenantRepository $repoapp,GroupeRepository $repogrp)
    {
        $this->repo=$repo;
        $this->repogrp=$repogrp;
        $this->repoapp=$repoapp;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}/groupe/{id1}/livrable",
     *     name="posturl",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\LivrableAttenduController::postUrl",
     *          "__api_resource_class"=LivrableAttendu::class,
     *          "__api_collection_operation_name"="posturl"
     *     }
     * )
     */

    public function postUrl(Request $request, int $id,int $id1)
    {
        $app=$this->repoapp->find($id);
        $grp=$this->repogrp->find($id1);
        $liv=json_decode($request->getContent(), true);
        $liv = $this->serializer->denormalize($liv, LivrableAttendu::class);
        $livattapp=$liv->getLivrableattenduapprenant();
        foreach ($app->getGroupe() as $groupe)
        {
            if($groupe->getId() === $grp->getId())
            {
                $appgrp = $grp->getApprenants();
                foreach ($appgrp as $apprenant)
                {
                    $livattapp->addApprenant($apprenant);
                    $this->manager->persist($livattapp);
                }
            }
        }
        $this->manager->flush();
        dd($livattapp->getApprenant());
        return $this->json($this->serializer->normalize($liv),Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}/groupe/{id1}/livrable",
     *     name="deleteurl",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\LivrableAttenduController::deleteUrl",
     *          "__api_resource_class"=LivrableAttendu::class,
     *          "__api_collection_operation_name"="deleteurl"
     *     }
     * )
     */

    public function deleteUrl(Request $request, int $id,int $id1)
    {
        $app=$this->repoapp->find($id);
        $grp=$this->repogrp->find($id1);
        $liv=json_decode($request->getContent(), true);
        $liv = $this->serializer->denormalize($liv, LivrableAttendu::class);
        $livattapp=$liv->getLivrableattenduapprenant();
        foreach ($app->getGroupe() as $groupe){
            if($groupe->getId() === $grp->getId()){
                $appgrp = $grp->getApprenants();
                foreach ($appgrp as $apprenant)
                {
                    $livattapp->removeApprenant($apprenant);
                    $this->manager->persist($livattapp);
                }
            }
        }
        $this->manager->flush();
        dd($livattapp->getApprenant());
        return $this->json($this->serializer->normalize($liv),Response::HTTP_CREATED);
    }
}