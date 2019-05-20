<?php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ClientController extends AbstractController{

    /**
     * @var ClientRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct( ClientRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/createClient", name="client.new")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, AuthenticationUtils $authenticationUtils){

        $user = new Client();
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $this->em->persist($user);
            $this->em->flush();
            $lastUsername = $authenticationUtils->getLastUsername();
            $error = $authenticationUtils->getLastAuthenticationError();
            $success = "Compte créer";
            return $this->render('Security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'success' => $success
            ]);
        }

        return $this->render('Security/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}