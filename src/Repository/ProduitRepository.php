<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use App\Form\ProduitSearchType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function tousLesProduitsDisponible(ProduitSearch $search)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.Inventaire > 0 AND p.Disponible = 1');

        if ($search->getMaxPrice()){
            $query = $query
                ->andWhere('p.Prix <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if($search->getCategories()->count() > 0){
            $k = 0;
            $categorieID = array();
            $text = null;
            $query = $query;
            foreach($search->getCategories() as $options){
                $categorieID[$k] = $options->id;
                $k++;
            }
            if($k == 1){
                    $query->andWhere("p.Categorie IN (:category)")
                    ->setParameter("category", $categorieID[0]);
            }
            else{
                $k--;
                while ($k >= 0){
                    if ($k != 0){
                        $text = $text . $categorieID[$k] . ',';
                    }
                    else{
                        $text = $text . $categorieID[$k];
                    }
                    $k--;
                }
                $query ->andWhere("p.Categorie IN (:text)")
                    ->setParameter('text', $text);
                dump($query);
            }
        }

        return $query->getQuery();

    }

    public function setInventaire($inventaire, $Id)
    {
        $query = $this->createQueryBuilder('p');

        $query->update()
        ->set('p.Inventaire', ':inventaire')
        ->where('p.id = :id')
        ->setParameter('inventaire', $inventaire)
        ->setParameter('id', $Id)
        ->getQuery()
        ->getResult();
    }

    public function verification($id, $inventaire)
    {
        if($inventaire == 0)
        {
            $query = $this->createQueryBuilder('p');
            $query->update()
            ->set('p.Disponible', 0)
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
            return false;
        }
        else
        {
            return true;
        }
    }

    public function afficherLaCategorie($id){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'Select c.Nom
            FROM App\Entity\Categorie c
            Where c.id = :id'
        )->setParameter('id', $id);

        return $query->execute();

    }
    public function trouverLaPhoto($id){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'Select p.Photo
            FROM App\Entity\Produit p
            Where p.id = :id'
        )->setParameter('id', $id);

        return $query->execute();

    }
    public function remmetreLaPhoto($photo){
        $query = $this->createQueryBuilder('p')
            ->update('p.Photo', $photo);

        return $query->getQuery();
    }

    public function getResultSold($id){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'Select r.Rabais, r.id
            FROM App\Entity\Rabais r
            WHERE r.rabaisProduit = :id'
        )->setParameter('id', $id);

        return $query->execute();
    }

    public function tousLesRabais(){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'Select r
            FROM App\Entity\Rabais r'
        );

        return $query->execute();
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
