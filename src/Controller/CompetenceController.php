<?php

namespace App\Controller;
use App\Entity\Brief;
use App\Repository\BriefRepository;
use App\Repository\CompetenceRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\NiveauRepository;
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
use App\Entity\Competence;


class CompetenceController extends AbstractController
{
    private $manager;
    private $repo;
    private $repocomp;
    private $serializer;
    public function __construct(SerializerInterface $serializer,EntityManagerInterface $manager, CompetenceRepository $repo,NiveauRepository $repocomp){
        $this->repo=$repo;
        $this->repocomp=$repocomp;
        $this->manager=$manager;
        $this->serializer=$serializer;
    }
    /**
     * @Route(
     *     path="/api/admin/competences/{id}",
     *     name="put",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::update",
     *          "__api_resource_class"=Competence::class,
     *          "__api_item_operation_name"="update"
     *     }
     * )
     */
    public function update(Request $request,int $id)
    {
        $com=json_decode($request->getContent(), true);
        $groupe=$this->repo->find($id);
        foreach ($com["niveau"] as $key=>$val){
            $nivo=$this->repocomp->find($val["id"]);
            if (isset($val["action"])){
                $nivo->setAction($val["action"]);
            }
            if (isset($val["libelle"])){
                $nivo->setLibelle($val["libelle"]);
            }
            if (isset($val["critereEvaluation"])){
                $nivo->setCritereEvaluation($val["critereEvaluation"]);
            }
            $groupe->addNiveau($nivo);
            $this->manager->persist($groupe);
        }
        $this->manager->flush();
        return $this->json($this->serializer->normalize($groupe),Response::HTTP_CREATED);
    }

}