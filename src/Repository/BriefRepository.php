<?php

namespace App\Repository;

use App\Entity\Brief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brief|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brief|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brief[]    findAll()
 * @method Brief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brief::class);
    }

    // /**
    //  * @return Brief[] Returns an array of Brief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Brief
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getBrouillon(int $id)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.formateur', 'f')
            ->andWhere('f.id = :id')
            ->andWhere("b.statut = 'brouillon'")
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getBriefPromo(int $id, int $id1)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.briefMaPromos', 'bp')
            ->andWhere('bp.promo = :id')
            ->andWhere('bp.brief = b.id')
            ->andWhere('bp.brief = :id1')
            ->setParameter('id', $id)
            ->setParameter('id1', $id1)
            ->getQuery()
            ->getResult();
    }

    public function getBriefPromoGroupe(int $id, int $id1)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.briefMaPromos', 'bp')
            ->join('bp.promo', 'p')
            ->join('b.briefMonGroupe','bg')
            ->join('bg.groupe','g')
            ->andWhere('p.id = :id')
            ->andWhere('g.id = :id1')
            ->setParameter('id', $id)
            ->setParameter('id1', $id1)
            ->getQuery()
            ->getResult();
    }
    public function getValide(int $id)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.formateur', 'f')
            ->andWhere('f.id = :id')
            ->andWhere("b.statut = 'valide'")
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    public function getBriefPromoform(int $id0,int $id, int $id1)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.briefMaPromos', 'bp')
            ->andWhere('b.formateur = :id0')
            ->andWhere('bp.promo = :id')
            ->andWhere('bp.brief = b.id')
            ->andWhere('bp.brief = :id1')
            ->setParameter('id0', $id0)
            ->setParameter('id', $id)
            ->setParameter('id1', $id1)
            ->getQuery()
            ->getResult();
    }

}
