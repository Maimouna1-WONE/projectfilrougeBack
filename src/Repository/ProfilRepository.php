<?php

namespace App\Repository;

use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profil[]    findAll()
 * @method Profil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    // /**
    //  * @return Profil[] Returns an array of Profil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profil
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByArchive($value,$limit,$page)
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;
        return $this->createQueryBuilder('u')
            ->andWhere('u.archive = :val')
            ->setParameter('val', $value)
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
}
