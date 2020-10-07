<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramEmbeddedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("module", EntityType::class, [
                "class"=>Module::class,
                "choice_label"=>"nom",
                "attr"=>["class"=>"form-control"],
                "label"=>"Choix du module :",
                "placeholder"=>"Choix du module",
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
            ->add("duree", null, [
                "attr"=>["class"=>"form-control"],
                "label"=>"Choix de la durée :",
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class"=>Programme::class
        ]);
    }
}
