<?php

namespace App\Repository;

use App\Entity\ProfilSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilSortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilSortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilSortie[]    findAll()
 * @method ProfilSortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilSortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilSortie::class);
    }

    // /**
    //  * @return ProfilSortie[] Returns an array of ProfilSortie objects
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
    public function findOneBySomeField($value): ?ProfilSortie
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getSortie(int $promoval)
    {
        return $this->createQueryBuilder('ps')
            ->select('ps,a,g')
            ->join('ps.apprenants', 'a')
            ->join('a.groupe', 'g')
            ->andWhere('g.promotion = :promoval')
            ->andWhere("g.type = 'principal'")
            ->setParameter('promoval', $promoval)
            ->getQuery()
            ->getResult()
            ;
    }


    public function getProPro(int $promoval,int $profilsorval)
    {
        return $this->createQueryBuilder('ps')
            ->select('ps,a,g,p')
            ->join('ps.apprenants', 'a')
            ->join('a.groupe', 'g')
            ->join('g.promotion', 'p')
            ->andWhere('p.id = :promoval')
            ->andWhere('ps.id = :profilsorval')
            ->setParameter('profilsorval', $profilsorval)
            ->setParameter('promoval', $promoval)
            ->getQuery()
            ->getResult()
            ;
    }
}
