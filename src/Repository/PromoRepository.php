<?php

namespace App\Repository;

use App\Entity\Apprenant;
use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    // /**
    //  * @return Promo[] Returns an array of Promo objects
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
    public function findOneBySomeField($value): ?Promo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function attente()
    {
        return $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->join('p.groupes', 'g')
            ->join('g.apprenants', 'a')
            ->andWhere("g.type = 'principal'")
            ->andWhere('a.isConnected = false')
            ->getQuery()
            ->getResult()
            ;
    }

    public function attenteOne(int $val)
    {
        return $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->join('p.groupes', 'g')
            ->join('g.apprenants', 'a')
            ->andWhere('a.isConnected = false')
            ->andWhere('p.id = :value')
            ->andWhere("g.type = 'principal'")
            ->andWhere('g.promotion = p.id')
            ->setParameter('value', $val)
            ->getQuery()
            ->getResult()
            ;
    }

    public function allprincipal()
    {
        return $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->join('p.groupes', 'g')
            ->join('g.apprenants', 'a')
            ->andWhere("g.type = 'principal'")
            ->andWhere('g.promotion = p.id')
            ->getQuery()
            ->getResult();
    }

    public function allprincipalOne(int $val)
    {
        return $this->createQueryBuilder('p')
            ->select('p,g,a')
            ->join('p.groupes', 'g')
            ->join('g.apprenants', 'a')
            ->andWhere('p.id = :value')
            ->andWhere("g.type = 'principal'")
            ->setParameter('value', $val)
            ->getQuery()
            ->getResult()
            ;
    }

    public function promogroupe(int $val, int $val1)
    {
        return $this->createQueryBuilder('p')
            ->select('p,g')
            ->join('p.groupes', 'g')
            ->andWhere('p.id = :value')
            ->andWhere('g.id = :value1')
            ->andWhere('g.promotion = p.id')
            ->setParameter('value', $val)
            ->setParameter('value1', $val1)
            ->getQuery()
            ->getResult()
            ;
    }
}
