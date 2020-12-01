<?php

namespace App\DataPersister;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class ReferentielDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Referentiel;
    }

    /**
     * @param Referentiel $data
     */
    public function persist($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
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