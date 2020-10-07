<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                "attr"=>["class"=>"form-control"],
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
            ->add('description', null, [
                "attr"=>["class"=>"form-control"],
                "constraints"=> [
                    new NotBlank([
                        'message' => 'Obligatoire',
                    ]),
                ]
            ])
            ->add("categorie", EntityType::class, [
                "class"=>Categorie::class,
                "choice_label"=>"nom",
                "placeholder"=>"Selectonnez un categorie",
                "attr"=>["class"=>"form-control"],
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
            'data_class' => Module::class,
        ]);
    }
}
