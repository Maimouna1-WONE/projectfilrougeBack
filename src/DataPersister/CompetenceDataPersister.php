<?php

namespace App\DataPersister;

use App\Entity\Competence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class CompetenceDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Competence;
    }

    /**
     * @param Competence $data
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
        $groupes=$data->getGroupeCompetences();
        foreach ($groupes as $groupe){
            $groupe->removeCompetence($data);
        }
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}