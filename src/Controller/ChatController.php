<?php

namespace App\Controller;
use App\Entity\Brief;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromoRepository;
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


class ChatController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repous;
    private $repop;
    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,
                                EntityManagerInterface $manager, ChatRepository $repo,
                                PromoRepository $repop,UserRepository $repous){
        $this->repo=$repo;
        $this->repous=$repous;
        $this->repop=$repop;
        $this->manager=$manager;
        $this->validator=$validator;
        $this->serializer=$serializer;
    }
    /**
     * @Route(
     *     path="/api/users/promos/{id}/apprenants/{id1}/chats",
     *     name="postchat",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ChatController::addChat",
     *          "__api_resource_class"=Chat::class,
     *          "__api_collection_operation_name"="postchat"
     *     }
     * )
     */
    public function addChat(Request $request)
    {
        //$chat = $request->request->all();
        $chat=json_decode($request->getContent(), true);
        $avatar = $request->files->get("avatar");
        if ($avatar){
            $avatar = fopen($avatar->getRealPath(),"rb");
            $brief["avatar"] = $avatar;
        }
        $chat = $this->serializer->denormalize($chat, Chat::class);
        $app=$this->get("security.token_storage")->getToken()->getUser();
        $chat->setUser($app);
        $chat->setPromo($app->getGroupe()[0]->getPromotion());
        $errors = $this->validator->validate($chat);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->manager->persist($chat);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
        return $this->json($this->serializer->normalize($chat),Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/users/promos/{id}/apprenants/{id1}/chats",
     *     name="getchat",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ChatController::getChat",
     *          "__api_resource_class"=Chat::class,
     *          "__api_collection_operation_name"="getchat"
     *     }
     * )
     */
    public function getChat(int $id,int $id1)
    {
        $ok=($this->repop->find($id))->getId();
        $ok1=($this->repous->find($id1))->getId();
        $com=$this->repo->getchat($ok,$ok1);
        //dd($com);
        return $this->json($com,Response::HTTP_OK);
    }

}