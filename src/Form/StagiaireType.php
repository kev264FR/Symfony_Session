<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Nom :",
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
            ->add('prenom', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Prenom :",
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
            ->add('email', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Email :",
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ])
                ]
            ])
            ->add('numero', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Numero de telephone :",
            ])
            ->add('adresse', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Adresse postale :",
            ])
            ->add('codePostal', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Code postal :",
            ])
            ->add('ville', null, [
                "attr"=>[
                    "class"=>"form-control"
                ],
                "label"=>"Ville :",
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
