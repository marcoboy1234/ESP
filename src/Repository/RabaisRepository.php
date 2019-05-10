<?php

namespace App\Repository;

use App\Entity\Rabais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rabais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rabais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rabais[]    findAll()
 * @method Rabais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RabaisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rabais::class);
    }

    // /**
    //  * @return Rabais[] Returns an array of Rabais objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rabais
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
