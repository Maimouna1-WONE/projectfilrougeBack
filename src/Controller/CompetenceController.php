<?php

namespace App\Controller;
use App\Entity\Niveau;
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

    /**
     * @Route(
     *     path="/api/admin/competences/{id}",
     *     name="putcmp",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\CompetenceController::putcmp",
     *          "__api_resource_class"=Competence::class,
     *          "__api_collection_operation_name"="putcmp"
     *     }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function putcmp(Request $request, int $id){
        $object = $this->repo->find($id);
        $comp = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        //dd($comp['libelle']);
        $grpcompobj = $object->getGroupeCompetences();
        if($comp['groupeCompetences']) {
            $tab =$comp['groupeCompetences'];
            foreach ($tab as $value) {
                $g = (integer)$value;
                unset($value);
                $grpcomp = $this->repog->find($g);
                $tabokk[] = $grpcomp;
            }
        }
        foreach ($tabokk as $keyok=>$valueok){
            foreach ($grpcompobj as $k => $v){
                if (in_array($v, $tabokk, true) === false){
                    $object->removeGroupeCompetence($v);
                }
                $object->addGroupeCompetence($valueok);
            }
        }
        $nivo= $object->getNiveau();
        foreach ($comp as $key=>$value){
            //dd($key);
            if ($key !== "groupeCompetences" && $key !== "niveau"){
                $ok = "set" . ucfirst($key);
                $object->$ok($value);
            }
            if ($key === "niveau") {
                //foreach ($nivo as $n) {
                $i=0;
                    foreach ($comp['niveau'] as $val) {
                        foreach ($val as $cle => $valeur) {
                            //dd($valeur);
                            $okk = "set" . ucfirst($cle);
                            //dd($okk);
                            $nivo[$i]->$okk($valeur);
                            $errors = $this->validator->validate($nivo[$i]);
                            if (count($errors)) {
                                $errors = $this->serializer->serialize($errors, "json");
                                return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
                            }
                            $object->addNiveau($nivo[$i]);
                            //$this->manager->persist($object);
                        }
                        $i++;
                    }
                //}
            }
        }
        //dd($object);
        $errors = $this->validator->validate($object);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($object);
        $this->manager->flush();
        return $this->json("modification reussi", Response::HTTP_CREATED);
    }
}
