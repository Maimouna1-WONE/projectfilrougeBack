<?php

namespace App\DataPersister;

use App\Entity\ProfilSortie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *
 */
class ProfilSortieDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;private $validator;private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,ValidatorInterface $validator,SerializerInterface $serializer
    ) {
        $this->_entityManager = $entityManager;$this->validator=$validator;$this->serializer=$serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ProfilSortie;
    }

    /**
     * @param User $data
     * @return JsonResponse
     */
    public function persist($data, array $context = [])
    {
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            $errorsString =$this->serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
        return new JsonResponse("profil de sortie ajouté avec succés",Response::HTTP_CREATED,[],true);

    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $data->setArchive(1);
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

}