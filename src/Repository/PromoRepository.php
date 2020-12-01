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
    public function getSortie(string $value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT distinct ps.libelle
            FROM App\Entity\Promo p
            JOIN App\Entity\Groupe g
            JOIN App\Entity\Apprenant a
            JOIN App\Entity\ProfilSortie ps
            WHERE g.promotion = :val
            GROUP BY ps.libelle'
        )
            ->setParameter('val', $value);
        return $query->getResult();
    }

    public function attente()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Apprenant a
            JOIN App\Entity\Groupe g
            JOIN App\Entity\Promo p
            WHERE a.isConnected = false'
        );
        return $query->getResult();
    }
    public function attenteOne(int $val)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Apprenant a
            JOIN App\Entity\Groupe g
            JOIN App\Entity\Promo p
            WHERE a.isConnected = false and p.id=: val'
        )
        ->setParameter('val',$val);
        return $query->getResult();
    }
}
