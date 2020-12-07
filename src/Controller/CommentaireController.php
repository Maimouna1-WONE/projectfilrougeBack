<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceValideeRepository;
use App\Repository\FormateurRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Repository\UserRepository;
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
use App\Entity\Chat;


class CommentaireController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoliv;
    private $repop;
    private $reporef;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, CommentaireRepository $repo,
                                PromoRepository $repop,LivrablePartielRepository $repoliv,
                                ReferentielRepository $reporef)
    {
        $this->repo=$repo;
        $this->reporef=$reporef;
        $this->repoliv=$repoliv;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *     name="comm",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\CommentaireController::getComm",
     *          "__api_resource_class"=Commentaire::class,
     *          "__api_collection_operation_name"="comm"
     *     }
     * )
     */

    public function getComm(int $id)
    {
        $ok=($this->repoliv->find($id))->getId();
        $comm=$this->repo->getcomm($ok);
        //dd($comm);
        return $this->json($comm,Response::HTTP_OK);
    }


    /**
     * @Route(
     *     path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *     name="postcomm",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\CommentaireController::postComm",
     *          "__api_resource_class"=Commentaire::class,
     *          "__api_collection_operation_name"="postcomm"
     *     }
     * )
     */

    public function postComm(Request $request)
    {
        $comm = json_decode($request->getContent(), true);
        $commentaire = $this->serializer->denormalize($comm, Commentaire::class);
        $form = ($this->get("security.token_storage")->getToken())->getUser();
        $commentaire->setFormateur($form);
        $commentaire->setCreatedAt(new \DateTime);
        $errors = $this->validator->validate($commentaire);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($commentaire);
        $this->manager->flush();
        return $this->json($this->serializer->normalize($commentaire),Response::HTTP_CREATED);
    }

}