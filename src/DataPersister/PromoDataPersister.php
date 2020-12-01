<?php

namespace App\DataPersister;

use App\Entity\Competence;
use App\Entity\Promo;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class PromoDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Promo;
    }

    /**
     * @param Promo $data
     */
    public function persist($data, array $context = [])
    {
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