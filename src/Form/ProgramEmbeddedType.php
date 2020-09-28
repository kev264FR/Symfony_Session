<?php

namespace App\Form;

use App\Entity\Programme;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramEmbeddedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("module", EntityType::class, [
                "class"=>Module::class,
                "choice_label"=>"nom",
            ])
            ->add("duree", null, [
                "action"=>"test"
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
