<?php

namespace App\Controller;
use App\Entity\FilDiscussion;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\ChatRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceValideeRepository;
use App\Repository\FilDiscussionRepository;
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


class FilDiscussionController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoliv;
    private $repop;
    private $reporef;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator,
                                EntityManagerInterface $manager, FilDiscussionRepository $repo,
                                PromoRepository $repop, LivrablePartielRepository $repoliv,
                                ReferentielRepository $reporef)
    {
        $this->repo = $repo;
        $this->reporef = $reporef;
        $this->repoliv = $repoliv;
        $this->repop = $repop;
        $this->manager = $manager;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

}