<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,UserPasswordEncoderInterface $encode ): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $pass=$encode->encodePassword($user,$user->getPassword());
            $user->setPassword($pass);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('default/inscription.html.twig', [
            'controller_name' => 'DefaultController',
            'form'=> $form->createView()
        ]);
    }
}
