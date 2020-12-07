<?php

namespace App\Controller;
use App\Entity\Referentiel;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ReferentielController extends AbstractController
{
    private $repo;
    private $serializer;
    private $validator;
    private $manager;
    private $repoGP;

    public function __construct(ReferentielRepository $repo,SerializerInterface $serializer,
                                EntityManagerInterface $manager,ValidatorInterface $validator,
                                GroupeCompetenceRepository $repoGP)
    {
        $this->repo = $repo;
        $this->repoGP = $repoGP;
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
     *          "__api_item_operation_name"="add"
     *     }
     * )
     */
     public function addref(Request $request)
     {
         $ref = $request->request->all();
         //$ref=json_decode($request->getContent(), true);
         $file = $request->files->get("programme");
         if ($file){
             $file = fopen($file->getRealPath(),"rb");
             $ref["programme"] = $file;
         }
         //dd($ref);
         $refer = $this->serializer->denormalize($ref, 'App\Entity\Referentiel');
         $errors = $this->validator->validate($refer);
         if (count($errors)){
             $errors = $this->serializer->serialize($errors,"json");
             return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
         }
         $this->manager->persist($refer);
         $this->manager->flush();
         if ($file){
             fclose($file);
         }
         //dd($refer);
         return $this->json($this->serializer->normalize($refer),Response::HTTP_CREATED);
     }
    /**
     * @Route(
     *     path="/api/admin/referentiels/{id}/groupecompetences/{id1}",
     *     name="refgp",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::grpcompRef",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_item_operation_name"="refgp"
     *     }
     * )
     */
    public function grpcompRef(int $id, int $id1)
    {
        $i = ($this->repo->find($id))->getId();
        $j = ($this->repoGP->find($id1))->getId();
        $sortie = $this->repo->getgrpref($i, $j);
        return $this->json($sortie, Response::HTTP_OK);
    }
}