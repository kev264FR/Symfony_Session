<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', null, [
                "label"=>"Intitulé de la formation :",
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
            ->add('dateDebut', null, [
                "label"=>"Date de début :",
                "widget"=>"single_text",
                "attr"=>[
                        "class"=>"form-control"
                ]
            ])
            ->add('dateFin', null, [
                "label"=>"Date de fin",
                "widget"=>"single_text",
                "attr"=>[
                    "class"=>"form-control"
                    ]
            ])
            ->add('place', null, [
                "label"=>"Nombre de places :",
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
