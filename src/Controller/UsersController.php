<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactType;
use App\Form\FilterType;
use App\Form\loupeType;
use App\Repository\ContactsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
*/
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(Request $request, ContactsRepository $contactsRepo): Response
    {
        $filtreForm = $this->createForm(FilterType::class) ;
        $loupeForm=$this->createForm(loupeType::class);

        $filtreForm->handleRequest($request);

        $loupeForm->handleRequest($request);

        if($loupeForm->isSubmitted() && $loupeForm->isValid() ){

        $contact=$contactsRepo->filtre($filtreForm->getData(),$loupeForm->getData());
        }
        
        if($filtreForm->isSubmitted() && $filtreForm->isValid() ){

            $contact=$contactsRepo->filtre($filtreForm->getData(),$loupeForm->getData());
        }

        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'filtreForm'=> $filtreForm->createView(),
            'loupeForm'=>$loupeForm->createView(),
            'contact'=> $contact
        ]);
    }

    /**
     * @Route("/contact/ajouter", name="ajouterContact")
     */
    public function ajouterContact(Request $request): Response
    {
        $contact=new Contacts();
        $contact->setDate(new DateTime());
        $form=$this->createForm(ContactType::class,$contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $contact->setUser($this->getUser());
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('users');
        }
        return $this->render('users/AjouterContact.html.twig', [
            'contactform' => $form->createView(),
        ]);
    }
}
