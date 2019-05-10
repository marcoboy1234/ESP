<?php

namespace App\Repository;

use App\Entity\AdresseClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdresseClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseClient[]    findAll()
 * @method AdresseClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdresseClient::class);
    }

    // /**
    //  * @return AdresseClient[] Returns an array of AdresseClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdresseClient
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
