<?php

namespace App\Controller;

use App\Form\ModifierPassType;
use App\Form\OubliePassType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

     /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UsersRepository $usersRepo )
    {
        $user = $usersRepo->findOneBy(['activation_token'=>$token]);

        if(!$user){
            throw $this->createNotFoundException('cet utilisateur n\'exicte pas');
        }
        $user->setActivationToken(null);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message','Vous avez bien activé votre compte');

        return $this->redirectToRoute('app_login');
    }

     /**
     * @Route("/Pass-oublie", name="oubliePass")
     */
    public function oubliePass(Request $request, UsersRepository $usersRepo,\Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createform(OubliePassType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $donnees= $form->getData();

            $user=$usersRepo->findOneByEmail($donnees['email']);

            if(!$user){
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("oubliePass");
            }

            $token= $tokenGenerator->generateToken();
            
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
              $this->addFlash('danger','une erreur est servenue : '. $e->getMessage());
              return $this->redirectToRoute("oubliePass");  
            }

            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/resetPass.html.twig',
                    ['token' => $token]
                ),
                'text/html'
            );

            $mailer->send($message);
            
            $this->addFlash('message','un e-mail de réinitialisation de mot de passe vous a été envoyé');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/oubliePass.html.twig', [
            'emailform'=> $form->createView()
        ]);
    }

    /**
     * @Route("/modifier-Pass/{token}", name="modifierPass")
     */
    public function resetPass(Request $request, UsersRepository $usersRepo, $token , UserPasswordEncoderInterface $encoder)
    {
        $user = $usersRepo->findOneBy(['reset_token'=>$token]);
        if(!$user){
            $this->addFlash('danger','le liens que vous avez rensegnié est invalide');
            return $this->redirectToRoute('app_login');
        }

        $form= $this->createForm(ModifierPassType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $donnees= $form->getData();

            $user->setResetToken(null);

            $user->setPassword($encoder->encodePassword($user,$donnees['NouveauMotDePasse']));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message','Mot de passe modifier avec succès');
            return $this->redirectToRoute('app_login');
        }        

        return $this->render('security/modifierPass.html.twig', [
            'passform'=> $form->createView()
        ]);
    }
}
