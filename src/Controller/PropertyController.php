<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use App\Entity\Rabais;
use App\Form\ProduitSearchType;
use App\Repository\ProduitRepository;
use App\Repository\RabaisRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var RabaisRepository
     */
    private $repositoryRabais;

    private $date;

    public function __construct(ProduitRepository $repository, ObjectManager $em, RabaisRepository $repositoryRabais)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->repositoryRabais = $repositoryRabais;
        $this->date = new \DateTime();
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route("/biens", name="properties.index")
     */
    public function index(PaginatorInterface $paginator, Request $request):Response
    {
        $search = new ProduitSearch();
        $form = $this->createForm(ProduitSearchType::class, $search);
        $form->handleRequest($request);
        $tousLesRabais = array();

        $lesProduit = $this->repository->findAll();
        $cpt = 0;
        foreach ($lesProduit as $value)
        {
            $disponible = $this->repository->verification($value->getId(), $value->getInventaire());
            if( $disponible == true )
            {
                $rabais = $this->repository->getResultSold($value->getId());
                if($rabais != null)
                {
                    $solde = $this->repository->getResultSold($value->getId());
                    foreach ($solde as $soldeValue) 
                    {
                        $leRabais = $this->repositoryRabais->find($soldeValue["id"]);
                        $nouveauPrix = (int)$value->getPrix() - (((int)$value->getPrix() * (int)$soldeValue["Rabais"]) / 100);
                    }
                    if($leRabais->getDateDeFin()->format('Y-m-d') >= $this->date->format('Y-m-d'))
                    {
                        $rabaisTab = array(
                            "Id" => $value->getId(),
                            "NouveauPrix" => $nouveauPrix,
                        );
                        $tousLesRabais[$cpt] = $rabaisTab;
                    }
                    else
                    {
                        $rabaisTab = array(
                            "Id" => $value->getId(),
                            "NouveauPrix" => $value->getPrix(),
                        );
                        $tousLesRabais[$cpt] = $rabaisTab;
                    }
                }
                else
                {
                    $sansRabais = array(
                        "Id" => $value->getId()
                    );
                    $aucunRabais{$cpt} = $sansRabais;
                }
                    $cpt++;
            }
        }

        $produit = $paginator->paginate(
            $this->repository->tousLesProduitsDisponible($search),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('Produit/index.html.twig',[
            'current_menu' => 'properties',
            'produits' => $produit,
            'rabais' => $tousLesRabais,
            'sansRabais' => $aucunRabais,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{id}", name="produit.show")
     * @param $slug
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $nouveauPrix = 0;
        $rabais = 0;
        $produit = $this->repository->find($id);
        $categorie = $this->repository->afficherLaCategorie($produit->getCategorie()->getId());
        $solde = $this->repository->getResultSold($id);
        foreach ($solde as $value) {
            $leRabais = $this->repositoryRabais->find($value["id"]);
            if ($leRabais->getDateDeFin()->format('Y-m-d') >= $this->date->format('Y-m-d')) {
                $prix = $produit->getPrix();
                $nouveauPrix = (int)$prix - (((int)$prix * (int)$value["Rabais"]) / 100);
                $rabais = $value["Rabais"];
            }
            else{
                $nouveauPrix = $produit->getPrix();
            }
        }

        return $this->render('Produit/show.html.twig', [
            'produit' => $produit,
            'categorie' => $categorie,
            'solde' => $nouveauPrix,
            'rabais' => $rabais,
            'current_menu' => 'properties'
        ]);
    }
}
