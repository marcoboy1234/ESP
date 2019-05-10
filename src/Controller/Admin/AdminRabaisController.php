<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Rabais;
use App\Form\RabaisType;
use App\Repository\RabaisRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRabaisController extends AbstractController
{
    /**
     * @Route("/adminProduit", name="rabais_index", methods={"GET"})
     * @param RabaisRepository $rabaisRepository
     * @return Response
     */
    public function index(RabaisRepository $rabaisRepository): Response
    {
        return $this->render('Admin/Poduit/index.html.twig', [
            'rabais' => $rabaisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/adminRabais/create/produit/{id}", name="admin.rabais.new", methods={"GET","POST"})
     * @param Request $request
     * @param Produit $produit
     * @return Response
     */
    public function new(Request $request, Produit $produit): Response
    {
        $rabai = new Rabais();
        $form = $this->createForm(RabaisType::class, $rabai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rabai->setRabaisProduit($produit);
            $rabai->setNouveauPrix( (int)$produit->getPrix() - (((int)$produit->getPrix() * (int)$rabai->getRabais()) / 100));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rabai);
            $entityManager->flush();

            return $this->redirectToRoute('rabais_index');
        }

        return $this->render('Admin/Rabais/new.html.twig', [
            'rabai' => $rabai,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.rabais,edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rabais $rabai): Response
    {
        $form = $this->createForm(RabaisType::class, $rabai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rabais_index', [
                'id' => $rabai->getId(),
            ]);
        }

        return $this->render('Rabais/edit.html.twig', [
            'rabai' => $rabai,
            'form' => $form->createView(),
        ]);
    }
}
