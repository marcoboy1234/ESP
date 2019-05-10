<?php
namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController{

    /**
     * @var ProduitRepository
     */

    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProduitRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/adminProduit", name="admin.produit.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $produit = $this->repository->findAll();
        return $this->render('Admin/Produit/index.html.twig', [
            'produits'=>$produit
        ]);
    }

    /**
     * @Route("/adminProduit/create", name="admin.produit.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function new(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($produit);
            $this->em->flush();
            $this->addFlash('success','Produit créer avec succès');
            return $this->redirectToRoute('admin.produit.index');
        }

        return $this->render('Admin/Produit/new.html.twig', [
            'produits'=>$produit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminProduit/produit/{id}", name="admin.produit.edit", methods="GET|POST")
     * @param Produit $produit
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Produit $produit, Request $request)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $id = $produit->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $produit->getPhoto();
            if($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $produit->setPhoto($fileName);
            }else{
                $fileName = $this->repository->trouverLaPhoto($id);
                foreach ($fileName as $value){
                    $produit->setPhoto($value['Photo']);
                    dump($value);
                }
            }
            $this->em->flush();
            $this->addFlash('success','Produit modifié avec succès');
            return $this->redirectToRoute('admin.produit.index');
        }

        return $this->render('Admin/Produit/edit.html.twig', [
            'produits'=>$produit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminProduit/produit/{id}", name="admin.produit.update")
     * @param Produit $produit
     * @return Response
     */
    public function update(Produit $produit){
        $entityManager = $this->getDoctrine()->getManager();
        if($produit->getDisponible() == '0') {
            $produit->setDisponible(1);
            $entityManager->flush();
        }
        else{
            $produit->setDisponible(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.produit.index');
    }
}