<?php

namespace App\Repository;

use App\Entity\CompetenceValidee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceValidee|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceValidee|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceValidee[]    findAll()
 * @method CompetenceValidee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceValideeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceValidee::class);
    }

    // /**
    //  * @return CompetenceValidee[] Returns an array of CompetenceValidee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompetenceValidee
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
