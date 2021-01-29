<?php

namespace App\Controller;
use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use App\Repository\ProfilRepository;
use App\Repository\ProfilSortieRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PromoController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoApp;
    private $repoPromo;
    private $profilsor;
    private $ref;
    public function __construct(UserPasswordEncoderInterface $encoder,
                                SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, ProfilRepository $repo,
                                ApprenantRepository $repoApp,PromoRepository $repoPromo,
                                ProfilSortieRepository $profilsor, ReferentielRepository $ref){
        $this->repo=$repo;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
        $this->repoApp=$repoApp;
        $this->profilsor=$profilsor;
        $this->repoPromo=$repoPromo;
        $this->ref = $ref;
    }

    /**
     * @Route(
     *     path="/api/admin/promos",
     *     name="promo_add",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::addPromo",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="add_promo"
     *     }
     * )
     */
    public function addPromo(Request $request, \Swift_Mailer $mailer, ProfilRepository $repoProfil)
    {
        $promocontent = $request->request->all();
        //$promocontent = json_decode($request->getContent(), true);
        //dd($promocontent["apprenant"]);
        $avatar = $request->files->get("avatar");
        if ($avatar){
            $avatar = fopen($avatar->getRealPath(),"rb");
        }
        $idRef = (int)$promocontent["referentiel"];
        //dd($idRef);
        unset($promocontent["referentiel"]);
        $referentiel = $this->ref->find($idRef);
        $promo = $this->serializer->denormalize($promocontent,"App\Entity\Promo");
        $promo->setReferentiel($referentiel);
        $promo->setDateDebut(new \DateTime);
        $promo->setDateFin(\DateTime::createFromFormat('Y-m-d', $promocontent['date_fin']));
        $promo->setLibelle($promocontent["libelle"]);
        $promo->setLieu($promocontent["lieu"]);
        $promo->setFabrique($promocontent["fabrique"]);
        $promo->setDescription($promocontent["description"]);
        $promo->setReferenceAgate($promocontent["reference_agate"]);
        $promo->setAvatar($avatar);
        $grp = new Groupe();
        $grp->setPromotion($promo);
        foreach ($promocontent["apprenant"] as $key => $value) {
            if ($promocontent["apprenant"][$key] !== "") {
                $app = new Apprenant($this->encoder);
                $app->setEmail($value);
                $app->setProfil($repoProfil->findOneByLibelle("APPRENANT"));
                $this->manager->persist($app);
                $grp->addApprenant($app);
                $this->manager->persist($grp);
            }
        }

        $doc = $request->files->get("document");
       $file= IOFactory::identify($doc);
       $reader= IOFactory::createReader($file);
       $spreadsheet=$reader->load($doc);
       $array_contenu_fichier= $spreadsheet->getActivesheet()->toArray();
       //return $array_contenu_fichier;
       for ($i=1, $iMax = count($array_contenu_fichier); $i< $iMax; $i++){
            $app= new Apprenant($this->encoder);
            $app ->setEmail($array_contenu_fichier[$i][0]);
            $app->setProfil($repoProfil->findOneByLibelle("APPRENANT"));
            $this->manager->persist($app);
            $grp->addApprenant($app);
            $this->manager->persist($grp);
       }

        $promo->addGroupe($grp);
        $errors = $this->validator->validate($promo);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }

        $password = "pass_1234";
        $message = (new\Swift_Message)
            ->setSubject('Orange Digital Center, SONATEL ACADEMY')
            ->setFrom('mainashou@gmail.com')
            ->setTo($app->getEmail())
            ->setBody("Bienvenue cher apprenant vous avez intégré la nouvelle promotion de la première école de codage gratuite du Sénégal, veuillez utiliser ce login: " . $app->getLogin() . " et ce password : " . $password . " par defaut pour se connecter");
        $mailer->send($message);

        $this->manager->persist($promo);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
        return $this->json("ajout reussi", Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/groupes/{id1}",
     *     name="putgroupe",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::modifie",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="putgroupe"
     *     }
     * )
     */

    public function modifie(int $id1, int $id)
    {
        $promo=$this->repoPromo->promogroupe($id, $id1);
        $groupes=$promo[0]->getGroupes();
        foreach ($groupes as $val){
                $val->setArchive(1);
        }
        $this->manager->persist($promo[0]);
        $this->manager->flush();
        return $this->json('Statut change', Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/apprenants/attente",
     *     name="attente",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::attentePro",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="attente"
     *     }
     * )
     */
    public function attentePro()
    {
        $sortie=$this->repoPromo->attente();
        return $this->json($sortie,Response::HTTP_OK, [] ,['groups' => ['attenteOne:read']]);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/apprenants/attente",
     *     name="attenteOne",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::attenteforOne",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="attenteOne"
     *     }
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function attenteforOne(int $id)
    {
        $value=$this->repo->find($id);
        $ok=$value->getId();
        $sortie=$this->repoPromo->attenteOne($ok);
        return $this->json($sortie,Response::HTTP_OK, [] ,['groups' => ['attenteOne:read']]);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/principal",
     *     name="getprincipal",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::principal",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="getprincipal"
     *     }
     * )
     */
    public function principal()
    {
        $sortie=$this->repoPromo->allprincipal();
        return $this->json($sortie,Response::HTTP_OK, [] ,['groups' => ['principal:read']]);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/principal",
     *     name="getprincipalOne",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::principalOne",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="getprincipalOne"
     *     }
     * )
     */
    public function principalOne(int $id)
    {
        //dd("ok");
        $value=$this->repo->find($id);
        $ok=$value->getId();
        //dd($ok);
        $sortie=$this->repoPromo->allprincipalOne($ok);
        return $this->json($sortie,Response::HTTP_OK, [] ,['groups' => ['principal:read']]);
    }
}