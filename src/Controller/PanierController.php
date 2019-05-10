<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Entity\LePanier;
use App\Repository\ProduitRepository;
use App\Repository\PanierRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;


class PanierController extends AbstractController
{
    /**
     * @var PanierRepository
     */
    private $repositoryPanier;
    /**
     * @var ObjectManager
     */
    private $em;

    private $panierFinal;

    private $session_panier;

    private $idSession = 0;

    private $memoirPanier;

    public function __construct(PanierRepository $repositoryPanier, ObjectManager $em)
    {
        $this->repositoryPanier = $repositoryPanier;
        $this->em = $em;
        $this->panierFinal = new Panier();
        $this->memoirPanier = new LePanier();
        $session_panier = new Session();
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['panier'])){
            $_SESSION['panier'] = array();
        }
        else{
            foreach ($_SESSION['panier'] as $key => $value) {
                $this->idSession++;
            }
        }
    }

    /**
     * @Route("/panier", name="panier.index")
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(): Response
    {
        if($_SESSION['panier'] != null){
            return $this->render('Panier/index.html.twig', [
                'lePanier' => $_SESSION['panier']
            ]);
        }
        else{
            return $this->render('Panier/index.html.twig');
        }
    }

    /**
     * @Route("/panier/confirmation", name="panier.confirmation")
     * @return Response
     */
    public function confirmation(ProduitRepository $repositoryProduit) : Response
    {
        //$idDuPanier = $this->repositoryPanier->foundLast();
        //foreach ($idDuPanier as $idPanier) {
        //    $id = $idPanier;
        //}
        $lepanier = array(
            'Id',
            'nom',
            'prix',
            'quantite'
        );
        $grandTotal = 0;
        $inventaire = 0;
        foreach ($_SESSION['panier'] as $lesProduits) {
            $produit = $repositoryProduit->find($lesProduits['Id']);
            $grandTotal = $lesProduits['prix'] + $grandTotal;
            $inventaire = $produit->getInventaire() - $lesProduits['quantite'];
            $repositoryProduit->setInventaire($inventaire, $lesProduits['Id']);
        };
        $this->panierFinal->setDateDachat(date("Y-m-d"));
        $this->panierFinal->setGrandTotal($grandTotal);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($this->panierFinal);
        $entityManager->flush();
        $this->ajouterMemoirPanier($repositoryProduit);
        $_SESSION = array();
        session_destroy();
        return $this->render('Panier/confirmation.html.twig');
    }

    public function ajouterMemoirPanier(ProduitRepository $repositoryProduit)
    {
        $cpt = 0;
        $entityManager = $this->getDoctrine()->getManager();
        $lesPanier = $this->repositoryPanier->findAll();
        foreach ($lesPanier as $value) {
            $id = $value->getid();
        }
        
        foreach ($_SESSION['panier'] as $lesProduits) {
            $produit = $repositoryProduit->find($lesProduits['Id']);
            $panier = $this->repositoryPanier->find($id);
            $produit->addPanier($this->memoirPanier);
            $panier->addPanier($this->memoirPanier);
            $this->memoirPanier->setQuantite($lesProduits['quantite']);
            $this->memoirPanier->setTotal($lesProduits['prix']);
            $entityManager->persist($this->memoirPanier);
            $entityManager->flush();
            $this->memoirPanier = new Lepanier();
        }
    }

    /**
     * @Route("/panier/{id}", name="panier.add.produit")
     * @param $id
     * @param $quantite
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function edit($id, Request $request, ProduitRepository $repositoryProduit): Response
    {
        $produit = $repositoryProduit->find($id);
        $trouver = $this->verification($produit);
        $nomDuProduit = $produit->getNom();
        $rabais = $repositoryProduit->getResultSold($id);
        $quantite = $request->request->get('inputGroupSelectQuantite');
        if($rabais != null)
        {
            $solde = $repositoryProduit->getResultSold($id);
                foreach ($solde as $soldeValue) {
                    $prix = ((int)$produit->getPrix() - (((int)$produit->getPrix() * (int)$soldeValue["Rabais"]) / 100)) * (int)$quantite;
                }
        }
        else
        {
            $prix = ($produit->getPrix()) * $quantite;
        }
        if($trouver == false)
        {
            $lepanier = array(
                'Id' => $produit->getId(),
                'nom' => $nomDuProduit,
                'prix' => $prix,
                'quantite' => $quantite
            );
            $_SESSION['panier'][$this->idSession] = $lepanier;
            $message = null;
        }
        else
        {
            $message = 'Vous avez déja sélectionner ce produit.';
        }
        return $this->render('Panier/index.html.twig', [
            'lePanier' => $_SESSION['panier'],
            'message' => $message
        ]);
    }

    public function verification($produit)
    {
        $trouver = false;
        foreach ($_SESSION['panier'] as $lesProduit) {
            if($produit->getNom() == $lesProduit['nom']){
                $trouver = true;
                dump($trouver);
            }
        }
        return $trouver;
    }

    /**
     * @Route("/retirer/{nomProduit}", name="panier.retirer")
     * @param $nomProduit
     * @return Response
     */
    public function retirer($nomProduit) : Response
    {
        $cpt = 0;
        $lepanier = array();
        foreach ($_SESSION['panier'] as $lesProduit) {
            if($lesProduit['nom'] != $nomProduit){
                $lepanier[$cpt] = array(
                    'Id' => $lesProduit['Id'],
                    'nom' => $lesProduit['nom'],
                    'prix' => $lesProduit['prix'],
                    'quantite' => $lesProduit['quantite']
                );
            }
            else
            {
                $cpt--;
            }
            $cpt++;
            $_SESSION['panier'] = $lepanier;    
        }
        dump($_SESSION['panier']);
        return $this->render('Panier/index.html.twig', [
            'lePanier' => $_SESSION['panier']
        ]);
    }
}