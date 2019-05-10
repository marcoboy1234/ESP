<?php

namespace App\Repository;

use App\Entity\LePanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LePanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method LePanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method LePanier[]    findAll()
 * @method LePanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LePanierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LePanier::class);
    }

    // /**
    //  * @return LePanier[] Returns an array of LePanier objects
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
    public function findOneBySomeField($value): ?LePanier
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
