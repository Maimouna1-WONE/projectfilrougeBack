<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CompetenceController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repog;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, CompetenceRepository $repo,
    GroupeCompetenceRepository $repog){
        $this->repo=$repo;
        $this->repog=$repog;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
     * @Route(
     *     path="/api/admin/competences",
     *     name="postcmp",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\CompetenceController::addcmp",
     *          "__api_resource_class"=Competence::class,
     *          "__api_collection_operation_name"="postcmp"
     *     }
     * )
     */
    public function addcmp(Request $request){
        $comp = json_decode($request->getContent(), true);
        //dd($comp);
        foreach ($comp["groupeCompetences"] as $key => $value){
            $idgrpComp = (int)$value;
            unset($value);
            $grpcomp = $this->repog->find($idgrpComp);
            $tab[] = $grpcomp;
        }
        unset($comp["groupeCompetences"]);
        //dd($tab);
        $competence = $this->serializer->denormalize($comp, Competence::class);
        $competence->setLibelle($comp["libelle"]);
        $competence->setDescription($comp["description"]);
        //dd($competence);
        foreach ($tab as $val){
            $competence->addGroupeCompetence($val);
        }
        $errors = $this->validator->validate($competence);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($competence);
        $this->manager->flush();
        return $this->json("ajout reussi", Response::HTTP_CREATED);
    }
}
