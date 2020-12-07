<?php

namespace App\Repository;

use App\Entity\LivrablePartiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrablePartiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrablePartiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrablePartiel[]    findAll()
 * @method LivrablePartiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrablePartielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrablePartiel::class);
    }

    // /**
    //  * @return LivrablePartiel[] Returns an array of LivrablePartiel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivrablePartiel
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getliv(int $id, int $id1, int $id2)
    {
        return $this->createQueryBuilder('l')
            ->select('l')
            ->join('l.birefmapromo', 'bp')
            ->join('l.apprenantLivrablePartiel', 'al')
            ->join('al.apprenant','a')
            ->join('bp.promo', 'p')
            ->join('bp.brief', 'b')
            ->andWhere('a.id = :id')
            ->andWhere('p.id = :id1')
            ->andWhere('b.id = :id2')
            ->setParameter('id', $id)
            ->setParameter('id1', $id1)
            ->setParameter('id2', $id2)
            ->getQuery()
            ->getResult();
    }

    public function getcomp(int $id, int $id1)
    {
        return $this->createQueryBuilder('l')
            ->select('l,bp,p,r,g,a')
            ->join('l.birefmapromo', 'bp')
            ->join('bp.promo', 'p')
            ->join('p.referentiel','r')
            ->join('p.groupes','g')
            ->join('g.apprenants','a')
            ->andWhere('g.promotion = p.id')
            ->andWhere("g.type = 'principal'")
            ->andWhere('p.id = :id')
            ->andWhere('r.id = :id1')
            ->setParameter('id', $id)
            ->setParameter('id1', $id1)
            ->getQuery()
            ->getResult();
    }
}
