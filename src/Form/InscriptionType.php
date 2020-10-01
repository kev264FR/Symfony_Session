<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stagiaires', CollectionType::class, [
                "entry_type"=>EntityType::class,
                "entry_options"=>[
                    "class"=>Stagiaire::class,
                    "choice_label"=>"fullName",
                    "label"=>false,
                    "attr"=>["class"=>"form-control"],
                    "placeholder"=>"selection du stagiaire"
                ],
                "allow_add"=>true,
                "allow_delete"=>true,
                "label"=>false,
                "attr"=>["label"=>false]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class"=>Session::class
        ]);
    }
}
