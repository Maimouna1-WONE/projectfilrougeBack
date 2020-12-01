<?php

namespace App\Controller;
use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use App\Repository\ProfilRepository;
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


class GroupeController extends AbstractController
{
    private $manager;
    private $repo;private $repo1;
    public function __construct(EntityManagerInterface $manager, GroupeRepository $repo,ApprenantRepository $repo1){
        $this->repo=$repo;
        $this->repo1=$repo1;
        $this->manager=$manager;
    }
    /**
     * @Route(
     *     path="/api/admin/groupes/{id}/apprenants/{id1}",
     *     name="delete",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeController::delete",
     *          "__api_resource_class"=Groupe::class,
     *          "__api_item_operation_name"="delete"
     *     }
     * )
     */
    public function delete(int $id,int $id1)
    {
        $object=$this->repo->find($id);
        $app=$this->repo1->find($id1);
        $allApp=$object->getApprenants();
        foreach ($allApp as $key)
        {
            if ($key==$app) {
                $object->removeApprenant($app);
            }
        }
        $this->manager->persist($object);
        $this->manager->flush();
        return new JsonResponse("suppression reussie",Response::HTTP_CREATED,[],true);
    }

}
