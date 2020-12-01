<?php

namespace App\Controller;
use App\Entity\GroupeTag;
use App\Entity\Referentiel;
use App\Repository\BriefRepository;
use App\Repository\CompetenceRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\GroupeTagRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Repository\TagRepository;
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
use App\Entity\Tag;


class ReferentielController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repogrpcomp;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, ReferentielRepository $repo,GroupeCompetenceRepository $repogrpcomp){
        $this->repo=$repo;
        $this->repogrpcomp=$repogrpcomp;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/admin/referentiels",
     *     name="add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::addref",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="add"
     *     }
     * )
     */
    public function addref(Request $request){
        $ref=json_decode($request->getContent(), true);
        $refer = $this->serializer->denormalize($ref, Referentiel::class);
        foreach ($ref["groupeCompetence"] as $value)
        {
            $grp = $this->repogrpcomp->find($value);
            $refer->addGroupeCompetence($grp);
        }
        $errors = $this->validator->validate($refer);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($refer);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($refer),Response::HTTP_CREATED);
    }


    /**
     * @Route(
     *     path="/api/admin/referentiels/{id}",
     *     name="update",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeTagController::deletegrpcomp",
     *          "__api_resource_class"=GroupeTag::class,
     *          "__api_item_operation_name"="update"
     *     }
     * )
     */
    public function deletegrpcomp(Request $request,int $id)
    {
        $com=json_decode($request->getContent(), true);
        //dd($com);
        $groupe=$this->repo->find($id);
        $da=$this->repogrpcomp->find($com["groupecompetence"][0]);
        if (isset($com["action"]) && $com["action"]==="delete")
        {
            $groupe->removeGroupeCompetence($da);
        }
        if (isset($com["action"]) && $com["action"]==="update")
        {
            $groupe->addGroupeCompetence($da);
        }
        if (isset($com["action"]) && $com["action"]==="assignate")
        {
            $groupe->addGroupeCompetence($this->repogrpcomp->find($da));
        }
        $this->manager->persist($groupe);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($groupe),Response::HTTP_CREATED);
    }

}