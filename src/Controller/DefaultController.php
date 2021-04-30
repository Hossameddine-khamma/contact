<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\loupeType;
use App\Form\UserEditType;
use App\Form\UsersType;
use App\Repository\ContactsRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $loupeForm=$this->createForm(loupeType::class);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'loupeForm'=>$loupeForm->createView(),
        ]);
    }
    
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,UserPasswordEncoderInterface $encode, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $pass=$encode->encodePassword($user,$user->getPassword());
            $user->setPassword($pass);
            $user->setUpdateAt(new DateTime('now'));

            $token=$tokenGenerator->generateToken();
            $user->setActivationToken($token);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/index.html.twig',
                    ['token' => $token]
                ),
                'text/html'
            );

            $mailer->send($message);
            return $this->redirectToRoute('modifier');
        }
        return $this->render('default/inscription.html.twig', [
            'controller_name' => 'DefaultController',
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/modifier", name="modifier")
     */
    public function modifier(Request $request, EntityManagerInterface $em, UsersRepository $usersRepo): Response
    {
        $user= $usersRepo->findOneBy(['id' => $this->getUser()->getId()]);
        if( $user->getActivationToken() != null ){
            $activer=false;
        }if( $user->getActivationToken() == null ){
            $activer=true;
        }
        

        $editform = $this->createForm(UserEditType::class,$user);

        $editform->handleRequest($request);

        if($editform->isSubmitted() && $editform->isValid()){
            $user->setUpdateAt(new DateTime('now'));
            
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile',['id' => $this->getUser()->getId()]);
        }

        return $this->render('users/EditUsers.html.twig', [
            'editform' => $editform->createView(),
            'activer'=> $activer
        ]);
    }

    /**
     * @Route("profil/{id}", name="profile")
     */
    public function profile(Request $request, ContactsRepository $contactsRepo, Users $user )
    {
        $loupeForm=$this->createForm(loupeType::class);

        return $this->render('users/profile.html.twig', [
            'loupeForm'=>$loupeForm->createView(),
                'user'=> $user

        ]);
        

    }

}
