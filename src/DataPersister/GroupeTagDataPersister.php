<?php

namespace App\DataPersister;

use App\Entity\GroupeTag;
use App\Entity\Taag;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class GroupeTagDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof GroupeTag;
    }

    /**
     * @param GroupeTag $data
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
        $groupes=$data->getTag();
        foreach ($groupes as $groupe)
        {
            $groupe->removeGroupeTag($data);
        }
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}