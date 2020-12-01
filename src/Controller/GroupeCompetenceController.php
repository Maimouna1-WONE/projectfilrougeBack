<?php

namespace App\Controller;
use App\Entity\Brief;
use App\Repository\BriefRepository;
use App\Repository\CompetenceRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeCompetenceRepository;
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


class GroupeCompetenceController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repocomp;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, GroupeCompetenceRepository $repo,CompetenceRepository $repocomp){
        $this->repo=$repo;
        $this->repocomp=$repocomp;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
     * @Route(
     *     path="/api/admin/groupecompetences/{id}",
     *     name="update",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::deletecomp",
     *          "__api_resource_class"=Brief::class,
     *          "__api_item_operation_name"="update"
     *     }
     * )
     */
    public function deletecomp(Request $request,int $id)
    {
        $com=json_decode($request->getContent(), true);
        $groupe=$this->repo->find($id);
        $da=$this->repocomp->find($com["competence"][0]);
        if (isset($com["action"]) && $com["action"]==="delete")
        {
            $groupe->removeCompetence($da);
        }
        if (isset($com["action"]) && $com["action"]==="update")
        {
            $groupe->addCompetence($da);
        }
        if (isset($com["action"]) && $com["action"]==="assignate")
        {
            $groupe->addCompetence($this->repocomp->find($da));
        }
        $this->manager->persist($groupe);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($groupe),Response::HTTP_CREATED);
    }

}