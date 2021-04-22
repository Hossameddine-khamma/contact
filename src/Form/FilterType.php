<?php

namespace App\Form;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom',HiddenType::class)
            ->add('Prenom',TelType::class,[
                'required'=>false,
            ])
            ->add('Mail',TextType::class,[
                'required'=>false,
            ])
            ->add('Telephone',TextType::class,[
                'required'=>false,
            ])
            ->add('Metier',TextType::class,[
                'required'=>false,
            ])
            ->add('ville',TextType::class,[
                'required'=>false,
            ])
            ->add('News',TextType::class,[
                'required'=>false,
            ])
            ->add('Date',DateType::class,[
                'widget' => 'single_text',
                'required'=>false,
            ])
            ->add('Meteo',TextType::class,[
                'required'=>false,
            ])
            ->add('Tags',TextType::class,[
                'required'=>false,
            ])
            ->add('Rechercher',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        
    }
}
