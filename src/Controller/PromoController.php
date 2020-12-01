<?php

namespace App\Controller;
use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use App\Repository\ProfilRepository;
use App\Repository\PromoRepository;
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
    public function __construct(UserPasswordEncoderInterface $encoder,
                                SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, ProfilRepository $repo, ApprenantRepository $repoApp,PromoRepository $repoPromo){
        $this->repo=$repo;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
        $this->repoApp=$repoApp;
        $this->repoPromo=$repoPromo;
    }
    /**
     * @Route(
     *     path="/api/admin/promos/{id}/profilsorties",
     *     name="promo_profil",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::showSortie",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="promo_profil"
     *     }
     * )
     */
    public  function showSortie(PromoRepository $repo, int $id){
        $i=($repo->find($id))->getId();
        $sortie=$repo->getSortie($i);
        return $this->json($sortie,Response::HTTP_OK);
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
        $promo = new Promo();
        $promo->setLibelle("Sonatel Academy1")
            ->setLangue("Francais")
            ->setDescription("nouvelle cohorte")
            ->setArchive(0);
        $groupe = new Groupe();
        $groupe->setArchive(1)
            ->setLibelle("principal")
            ->setPeriode("1 semaine");

        /*$tab=["wone.maimouna@ugb.edu.sn"];
        for($i=0;$i<1;$i++) {
        $apprenant = new Apprenant();
        $apprenant->addGroupe($groupe)
        ->setStatut("actif");
        $password = "pass_1234";
        $apprenant->setLogin("mainashou".$i)
        ->setNom("SHOU".$i)
        ->setPrenom("Maina".$i)
        ->setTelephone("777460900")
        ->setGenre("F")
        ->setAdresse("Hann")
        ->setEmail($tab[$i])
        ->setPassword($this->encoder->encodePassword($apprenant, $password));
        }*/

        /*$doc = $request->files->get("document");
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $array_contenu_fichier= $spreadsheet->getActivesheet()->toArray();
        //dd($array_contenu_fichier);
        $password="pass_1234";
        for ($i=1;$i<count($array_contenu_fichier);$i++){
        $apprenant = new Apprenant();
        $apprenant->addGroupe($groupe)
        ->setStatut("actif")
        ->setLogin($array_contenu_fichier[$i][0])
        ->setPassword($this->encoder->encodePassword($apprenant,$password))
        ->setNom($array_contenu_fichier[$i][1])
        ->setPrenom($array_contenu_fichier[$i][2])
        ->setTelephone($array_contenu_fichier[$i][3])
        ->setAdresse($array_contenu_fichier[$i][4])
        ->setGenre($array_contenu_fichier[$i][5])
        ->setEmail($array_contenu_fichier[$i][6]);
        }*/

        $apprenant->setProfil($repoProfil->findOneByLibelle("APPRENANT"));
        $groupe->addApprenant($apprenant);

        $this->manager->persist($apprenant);

        $message = (new\Swift_Message)
            ->setSubject('Orange Digital Center, SONATEL ACADEMY')
            ->setFrom('mainashou@gmail.com')
            ->setTo($apprenant->getEmail())
            ->setBody("Bienvenue cher apprenant vous avez intégré la nouvelle promotion de la première école de codage gratuite du Sénégal, veuillez utiliser ce login: " . $apprenant->getLogin() . " et ce password : " . $password . " par defaut pour se connecter");
        $mailer->send($message);

        $promo->addGroupe($groupe);
        $errors = $this->validator->validate($promo);
        if (count($errors)) {
            $errors = $this->serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
        $this->manager->persist($groupe);
        $this->manager->persist($promo);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($promo), Response::HTTP_CREATED);
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

    public function modifie(GroupeRepository $repo,int $id1, PromoRepository $rep, int $id)
    {
        $groupe=$repo->find($id1);
        $promo=$rep->find($id);
        $groupes=$promo->getGroupes();
        foreach ($groupes as $val){
            if ($val===$groupe) {
                $val->setArchive(1);
            }
        }
        $this->manager->persist($promo);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($promo), Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/apprenants/{id1}",
     *     name="putgroupe",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::update",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="putgroupe"
     *     }
     * )
     */

    public function update(int $id1,int $id)
    {
        $promo=$this->repoPromo->find($id);
        $ok=$promo->getGroupes()->getValues();
        $app=$this->repoApp->find($id1);
        foreach ($ok as $val){
            dd($val);
        }
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
        return $this->json($sortie,Response::HTTP_OK);
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
     * @param int $val
     * @return JsonResponse
     */
    public function attenteforOne(int $val)
    {
        dd("ok");
        $value=$this->repo->find($val);
        dd($val);
        $sortie=$this->repoPromo->attenteOne($value);
        return $this->json($sortie,Response::HTTP_OK);
    }
}