<?php

namespace App\Controller;
use App\Entity\CompetenceValidee;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\CompetenceValideeRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
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
use App\Entity\Chat;


class CompetenceValideeController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoapp;
    private $repop;
    private $reporef;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, CompetenceValideeRepository $repo,
                                PromoRepository $repop,ApprenantRepository $repoapp,
                                ReferentielRepository $reporef)
    {
        $this->repo=$repo;
        $this->reporef=$reporef;
        $this->repoapp=$repoapp;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}/promos/{id1}/referentiels/{id2}/competences",
     *     name="comp",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\CompetenceValideeController::getComp",
     *          "__api_resource_class"=CompetenceValidee::class,
     *          "__api_collection_operation_name"="comp"
     *     }
     * )
     */

    public function getComp(int $id,int $id1,int $id2)
    {
        //dd($this->repo->findAll());
        $ok=($this->repoapp->find($id))->getId();
        $ok1=($this->repop->find($id1))->getId();
        $ok2=($this->reporef->find($id2))->getId();
        $comp=$this->repo->getcomp($ok,$ok1,$ok2);
        dd($comp);
        return $this->json($comp,Response::HTTP_OK);
    }

}