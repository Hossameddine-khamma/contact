<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',VichImageType::class,[
                'required' =>false
            ])
            ->add('email',TextType::class,[
                'required'=>false,
                'constraints' => [

                    new Regex([
                        'pattern' => '/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/',
                        'match' => true,
                        'message' => 'veuillez saisir une adresse mail correcte',
                    ]),
                ]
            ])
            ->add('Nom')
            ->add('Prenom')
            ->add('telephone',TextType::class,[
                'required'=>false,
                'constraints' => [

                    new Regex([
                        'pattern' => '/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/',
                        'match' => true,
                        'message' => 'veuillez saisir un numéro de téléphone correcte',
                    ]),
                ]
            ])
            ->add('ville')
            ->add('Metier')
            ->add('Tags')
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
