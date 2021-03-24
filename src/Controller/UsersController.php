<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/contact/ajouter", name="ajouterContact")
     */
    public function ajouterContact(): Response
    {
        $contact=new Contacts();
        $contact->setDate(new DateTime());
        $form=$this->createForm(ContactType::class,$contact);
        return $this->render('users/AjouterContact.html.twig', [
            'contactform' => $form->createView(),
        ]);
    }
}
