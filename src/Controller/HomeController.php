<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\RabaisRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $repositoryProduit;
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var RabaisRepository
     */
    private $repositoryRabais;

    private $date;

    public function __construct(ProduitRepository $repositoryProduit, ObjectManager $em, RabaisRepository $repositoryRabais)
    {
        $this->repositoryProduit = $repositoryProduit;
        $this->em = $em;
        $this->repositoryRabais = $repositoryRabais;
        $this->date = new \DateTime();
    }


    /**
     * @Route("/", name="home")
     * @param PaginatorInterface $paginator
     * @return Response
     */

    public function index(): Response
    {

        $lesProduits = $this->repositoryProduit->findAll();
        $cpt = 0;
        $tousLesRabais = array();

        foreach ($lesProduits as $value){
            $disponible = $this->repositoryProduit->verification($value->getId(), $value->getInventaire());
            if( $disponible == true and $value->getDisponible() == true)
            {
                $rabais = $this->repositoryProduit->getResultSold($value->getId());
                if($rabais != null)
                {
                    foreach ($rabais as $rabaisValue) 
                    {
                        $leRabais = $this->repositoryRabais->find($rabaisValue["id"]);
                        $nouveauPrix = (int)$value->getPrix() - (((int)$value->getPrix() * (int)$rabaisValue["Rabais"]) / 100);
                    }
                    if($leRabais->getDateDeFin()->format('Y-m-d') >= $this->date->format('Y-m-d'))
                    {
                        $rabaisTab = array(
                            "Id" => $value->getId(),
                            "Nom" => $value->getNom(),
                            "Description" => $value->getDescription(),
                            "NouveauPrix" => $nouveauPrix,
                        );
                        $tousLesRabais[$cpt] = $rabaisTab;
                        $cpt++;
                    }
                }
            }
        }

//        $produit = $paginator->paginate(
//            $request->query->getInt('page', 1),
//            6
//        );
        return $this->render('Pages/home.html.twig', [
            'rabais' => $tousLesRabais
        ]);
    }
}