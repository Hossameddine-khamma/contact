<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\Users;
use App\Form\ContactType;
use App\Form\FilterType;
use App\Form\loupeType;
use App\Form\UserEditType;
use App\Repository\ContactsRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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

        $contacts=$contactsRepo->filtre($filtreForm->getData(),$loupeForm->getData(),$this->getuser()->getId());
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'filtreForm'=> $filtreForm->createView(),
            'loupeForm'=>$loupeForm->createView(),
            'contacts'=> $contacts
        ]);
        }
        
        if($filtreForm->isSubmitted() && $filtreForm->isValid() ){

            $contacts=$contactsRepo->filtre($filtreForm->getData(),$loupeForm->getData(),$this->getuser()->getId());
            return $this->render('users/index.html.twig', [
                'controller_name' => 'UsersController',
                'filtreForm'=> $filtreForm->createView(),
                'loupeForm'=>$loupeForm->createView(),
                'contacts'=> $contacts
            ]);
        }

        $contacts= $contactsRepo->findBy(['user'=>$this->getuser()->getId()]);
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'filtreForm'=> $filtreForm->createView(),
            'loupeForm'=>$loupeForm->createView(),
            'contacts'=> $contacts
        ]);
    }

    /**
     * @Route("/ajouter", name="ajouterContact")
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
            $contact->setUpdateAt(new \DateTime('now'));
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('users');
        }
        return $this->render('users/AjouterContact.html.twig', [
            'contactform' => $form->createView(),
            'title'=>'veuillez ajouter votre contact'
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="ajouterContactauto")
     */
    public function ajouterContactauto($id, Request $request, UsersRepository $UsersRepo): Response
    {
        $oldContact=$UsersRepo->findOneBy(['id'=>$id]);
        $contact=new Contacts();

        $contact->setDate(new DateTime());
        $contact->setNom($oldContact->getNom());
        $contact->setPrenom($oldContact->getPrenom());
        $contact->setMetier($oldContact->getMetier());
        $contact->setTelephone($oldContact->getTelephone());
        $contact->setVille($oldContact->getVille());
        $contact->setMail($oldContact->getEmail());
        $contact->setTags($oldContact->getTags());

        $form=$this->createForm(ContactType::class,$contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $contact->setUser($this->getUser());
            $contact->setUpdateAt(new \DateTime('now'));
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('users');
        }
        return $this->render('users/AjouterContact.html.twig', [
            'contactform' => $form->createView(),
            'title'=>'veuillez ajouter votre contact'
        ]);
    }

    /**
     * @Route("/contact/{id}", name="contact")
     */
    public function contact($id, Request $request, EntityManagerInterface $em, ContactsRepository $contactsRepo): Response
    {
        $contact=$contactsRepo->findOneBy(['id'=>$id]);

        $loupeForm=$this->createForm(loupeType::class);

        return $this->render('users/contact.html.twig', [
            'loupeForm'=>$loupeForm->createView(),
                'contact'=> $contact

        ]);

    }
    /**
     * @Route("/contact/modifier/{id}", name="contactModifier")
     */
    public function contactModifier($id, Request $request, EntityManagerInterface $em, ContactsRepository $contactsRepo): Response
    {
        $contact=$contactsRepo->findOneBy(['id'=>$id]);
        
        $form=$this->createForm(ContactType::class,$contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $contact->setUser($this->getUser());
            $contact->setUpdateAt(new DateTime('now'));
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('users');
        }
        return $this->render('users/AjouterContact.html.twig', [
            'contactform' => $form->createView(),
            'title'=>'veuillez modifier votre contact'
        ]);
    }
}
