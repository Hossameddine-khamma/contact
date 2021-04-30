<?php

namespace App\Form;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',VichImageType::class,[
                'required'=>false
            ])
            ->add('Nom')
            ->add('Prenom')
            ->add('Mail',TextType::class,[
                'required'=>false,
                'constraints' => [

                    new Regex([
                        'pattern' => '/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/',
                        'match' => true,
                        'message' => 'veuillez saisir une adresse mail correcte',
                    ]),
                ]
            ])
            ->add('Telephone',TextType::class,[
                'required'=>false,
                'constraints' => [

                    new Regex([
                        'pattern' => '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/',
                        'match' => true,
                        'message' => 'veuillez saisir un numéro de téléphone correcte',
                    ]),
                ]
            ])
            ->add('Metier')
            ->add('ville')
            ->add('News',HiddenType::class)
            ->add('Date',DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('Meteo')
            ->add('Tags',TextareaType::class)
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contacts::class,
        ]);
    }
}
