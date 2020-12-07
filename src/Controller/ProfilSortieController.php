<?php

namespace App\Controller;
use App\Entity\ProfilSortie;
use App\Repository\ProfilSortieRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProfilSortieController extends AbstractController
{
    private $profilsor;
    private $repo;

    public function __construct(ProfilSortieRepository $profilsor,
                                PromoRepository $repo)
    {
        $this->profilsor = $profilsor;
        $this->repo = $repo;
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/profilsorties/{id1}",
     *     name="propro",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::showProPro",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="propro"
     *     }
     * )
     */
    public function showProPro(int $id, int $id1)
    {
        $i = ($this->repo->find($id))->getId();
        $j = ($this->profilsor->find($id1))->getId();
        $sortie = $this->profilsor->getProPro($i, $j);
        return $this->json($sortie, Response::HTTP_OK);
    }

    /**
     * @Route(
     *     path="/api/admin/promos/{id}/profilsorties",
     *     name="promo_profil",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::showSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="promo_profil"
     *     }
     * )
     */

    public  function showSortie(int $id){
        $i=($this->profilsor->find($id))->getId();
        //dd($i);
        $sortie=$this->profilsor->getSortie($i);
        return $this->json($sortie,Response::HTTP_OK);
    }

}
