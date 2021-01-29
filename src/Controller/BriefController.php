<?php

namespace App\Controller;
use App\Entity\Brief;
use App\Entity\Formateur;
use App\Entity\User;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BriefController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoform;
    private $repop;
    private $grp;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, BriefRepository $repo,
                                PromoRepository $repop,FormateurRepository $repoform,
                                GroupeRepository $grp){
        $this->repo=$repo;
        $this->grp=$grp;
        $this->repoform=$repoform;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
    * @Route(
    *     path="/api/formateurs/briefs",
    *     name="postbrief",
    *     methods={"POST"},
    *     defaults={
    *          "__controller"="App\Controller\BriefController::addBrief",
    *          "__api_resource_class"=Brief::class,
    *          "__api_collection_operation_name"="postbrief"
    *     }
    * )
    */

    public function addBrief(Request $request)
    {
        //$brief = $request->request->all();
        $brief=json_decode($request->getContent(), true);
        $avatar = $request->files->get("avatar");
        if ($avatar){
        $avatar = fopen($avatar->getRealPath(),"rb");
        $brief["avatar"] = $avatar;
        }
        $brief = $this->serializer->denormalize($brief,"App\Entity\Brief");
        $brief->setDateCreation(new \DateTime);
        $formateur=($this->get("security.token_storage")->getToken())->getUser();
        $brief->setFormateur($formateur);
        $errors = $this->validator->validate($brief);
        if (count($errors)){
        $errors = $this->serializer->serialize($errors,"json");
        return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($brief);
        $this->manager->flush();
        if ($avatar){
        fclose($avatar);
        }
        //dd($brief);
        return $this->json($this->serializer->normalize($brief),Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/formateurs/briefs/{id}",
     *     name="duplique",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::dupliqueBrief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="duplique"
     *     }
     * )
     */

    public function dupliqueBrief(int $id)
    {
        $duplique=$this->repo->find($id);
        $plus=clone $duplique;
        $this->manager->persist($plus);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($plus), Response::HTTP_CREATED);
    }


    /**
     * @Route(
     *     path="/api/formateurs/{id}/briefs/brouillon",
     *     name="brouillon",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::brouillons",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="brouillon"
     *     }
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function bouillons(int $id): JsonResponse
    {
        $ok=($this->repoform->find($id))->getId();
        $br=$this->repo->getbrouillon($ok);
        //dd($br);
        return $this->json($br,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/briefs/{id1}",
     *     name="brpm",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getbriefpromo",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="brpm"
     *     }
     * )
     * @param int $id
     * @param int $id1
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getbriefpromo(int $id, int $id1): JsonResponse
    {
        $ok=($this->repo->find($id))->getId();
        $ok1=($this->repop->find($id1))->getId();
        //dd($ok1);
        $br=$this->repo->getBriefPromo($ok,$ok1);
        //dd($br);
        return $this->json($this->serializer->normalize($br),Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/groupe/{id1}/briefs",
     *     name="brprgr",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getbriefpromogroupe",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="brprgr"
     *     }
     * )
     * @param int $id
     * @param int $id1
     * @return JsonResponse
     */
    public function getbriefpromogroupe(int $id, int $id1): JsonResponse
    {
        $ok=($this->repop->find($id))->getId();
        $ok1=($this->grp->find($id1))->getId();
        //dd($ok1);
        $br=$this->repo->getBriefPromoGroupe($ok,$ok1);
        dd($br);
        return $this->json($br,Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/formateurs/{id}/briefs/valide",
     *     name="valide",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::valides",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="brouillon"
     *     }
     * )
     * @param int $id
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function valides(int $id): JsonResponse
    {
        $ok=($this->repoform->find($id))->getId();
        $br=$this->repo->getValide($ok);
        dd($br);
        return $this->json($this->serializer->normalize($br),Response::HTTP_OK);
    }
    /**
     * @Route(
     *     path="/api/formateurs/{id0}/promo/{id}/briefs/{id1}",
     *     name="brpmf",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getbriefpromoform",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="brpmf"
     *     }
     * )
     * @param int $id
     * @param int $id1
     * @return JsonResponse
     */
    public function getbriefpromoform(int $id0,int $id, int $id1): JsonResponse
    {
        $ok0=($this->repoform->find($id0))->getId();
        $ok=($this->repo->find($id))->getId();
        $ok1=($this->repop->find($id1))->getId();
        //dd($ok1);
        $br=$this->repo->getBriefPromoform($ok0,$ok,$ok1);
        dd($br);
        return $this->json($br,Response::HTTP_OK);
    }
    /**
     * @Route(
     *     path="/api/formateurs/promo/{id}/briefs/{id1}/assignation",
     *     name="assigne",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::Asignation",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="assigne"
     *     }
     * )
     */
    public function Asignation(int $id,int $id1)
    {
        //dd('ok');
        $ok=($this->repo->find($id));
        $ok1=($this->repop->find($id1));
        foreach ($ok->getBriefMaPromos() as $briefMaPromo)
        {
            if ($briefMaPromo->getBrief()->getId() === $ok->getId() && $briefMaPromo->getPromo()->getId() === $ok1->getId())
            {
                $grp=$briefMaPromo->getPromo()->getGroupes();
                foreach ($grp as $groupe)
                {
                    if ($groupe->getType() === "principal")
                    {
                        $app=$groupe->getApprenants();
                        foreach ($app as $apprenant)
                        {
                            $apprenant->setBriefApprenant($briefMaPromo->getBriefApprenant());
                        }
                    }
                    if ($groupe->getType() === "secondaire")
                    {
                        dd('secondaire');
                    }
                }
            }
        }
    }

}