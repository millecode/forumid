<?php

namespace App\Form\Admin;

use App\Entity\EventDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date début',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control form-control-lg rounded-4'],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date fin',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control form-control-lg rounded-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventDate::class,
        ]);
    }
}
