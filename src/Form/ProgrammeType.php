<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Programme;
use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("programmes", CollectionType::class, [
                "entry_type"=>ProgramEmbeddedType::class,
                "allow_add"=>true,
                "allow_delete"=>true,
                'entry_options' => [
                    'label' => false,
                ],
                "label"=>false,
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
