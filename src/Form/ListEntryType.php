<?php

namespace App\Form;

use App\Entity\ListEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('comment')
            ->add(
                'closeDue',
                DateTimeType::class,
                [
                    'required'=>false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListEntry::class,
            'allow_extra_fields'    =>  true,
            'csrf_protection'       =>  false
        ]);
    }
}
