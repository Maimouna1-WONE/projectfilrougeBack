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
         $file = $request->files->get("programme");
         if ($file){
             $file = fopen($file->getRealPath(),"rb");
         }

         $tab= explode(',', $ref['groupeCompetence']);
         unset($ref['groupeCompetence']);
         foreach ($tab as $key=>$value){
             $grp = (integer)$value;
             unset($value);
             $grpcomp = $this->repoGP->find($grp);
             $tabok[]=$grpcomp;
         }
         $refer = $this->serializer->denormalize($ref, 'App\Entity\Referentiel');
            foreach ($tabok as $keyok=>$valueok){
                $refer->addGroupeCompetence($valueok);
}
         $refer->setProgramme($file);
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
         return $this->json("Ajout reussi",Response::HTTP_CREATED);
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

    /**
     * @Route(
     *     path="/api/admin/referentiels/{id}",
     *     name="put",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::putref",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_item_operation_name"="put"
     *     }
     * )
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function putref(int $id, Request $request)
    {
        $object = $this->repo->find($id);
        $ref = $request->request->all();
        $file = $request->files->get("programme");
        if ($file){
            $file = fopen($file->getRealPath(),"rb");
            $object->setProgramme($file);
        }
        $grpobj = $object->getGroupeCompetence();
        if($ref['groupeCompetence']) {
            //$tab = explode(',', $ref['groupeCompetence']);
            $tab =$ref['groupeCompetence'];
            //dd($tab);
            //unset($ref['groupeCompetence']);
            //if (is_array($tab)){
                foreach ($tab as $value) {
                    $grp = (integer)$value;
                    unset($value);
                    $grpcomp = $this->repoGP->find($grp);
                    $tabokk[] = $grpcomp;
                }
            //}
        }
        foreach ($ref as $key=>$value){
            if ($key !== "groupeCompetence" && $key !== "programme"){
            $ok = "set" . ucfirst($key);
            $object->$ok($value);
        }
        }
        foreach ($tabokk as $keyok=>$valueok){
            foreach ($grpobj as $k => $v){
                if (in_array($v, $tabokk, true) === false){
                    $object->removeGroupeCompetence($v);
                }
                $object->addGroupeCompetence($valueok);
            }
        }
        $errors = $this->validator->validate($object);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($object);
        $this->manager->flush();
        if ($file){
            fclose($file);
        }
        //dd($object->getGroupeCompetence());
        return $this->json('modification reussie', Response::HTTP_OK);
    }
}