<?php

namespace App\Controller;
use App\Entity\LivrablePartiel;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\FormateurRepository;
use App\Repository\LivrablePartielRepository;
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


class LivrablePartielController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $reporef;
    private $repob;
    private $repop;
    private $repoapp;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, LivrablePartielRepository $repo,
                                PromoRepository $repop,BriefRepository $repob,ReferentielRepository $reporef,
                                ApprenantRepository $repoapp){
        $this->repo=$repo;
        $this->reporef=$reporef;
        $this->repob=$repob;
        $this->repoapp=$repoapp;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/apprenants/{id}/promo/{id1}/briefs/{id2}",
     *     name="getliv",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::getLiv",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_collection_operation_name"="getliv"
     *     }
     * )
     */
    public function getLiv(int $id,int $id1,int $id2)
    {
        $ok=($this->repoapp->find($id))->getId();
        $ok1=($this->repop->find($id1))->getId();
        $ok2=($this->repob->find($id2))->getId();
        $liv=$this->repo->getliv($ok,$ok1,$ok2);
        //dd($liv);
        return $this->json($liv,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/referentiel/{id1}/statistiques/competences",
     *     name="statis",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::getComp",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_collection_operation_name"="statis"
     *     }
     * )
     */
    public function getComp(int $id,int $id1)
    {
        //dd('ok');
        $ok=($this->reporef->find($id1))->getId();
        $ok1=($this->repop->find($id1))->getId();
        $liv=$this->repo->getcomp($ok,$ok1);
        dd($liv);
        return $this->json($liv,Response::HTTP_OK);
    }



}