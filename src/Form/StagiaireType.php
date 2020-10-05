<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Nom :"
            ])
            ->add('prenom', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Prenom :"
            ])
            ->add('email', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Email :"
            ])
            ->add('numero', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Numero de telephone :"
            ])
            ->add('adresse', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Adresse postale :"
            ])
            ->add('codePostal', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Code postal :"
            ])
            ->add('ville', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Ville :"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
