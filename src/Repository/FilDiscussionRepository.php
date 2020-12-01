<?php

namespace App\Repository;

use App\Entity\FilDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FilDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilDiscussion[]    findAll()
 * @method FilDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilDiscussion::class);
    }

    // /**
    //  * @return FilDiscussion[] Returns an array of FilDiscussion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FilDiscussion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
