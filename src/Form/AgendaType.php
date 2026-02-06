<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\EventDate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventDate', EntityType::class, [
                'class' => EventDate::class,
                'placeholder' => 'Choisir une date (EventDate)',
                'choice_label' => function (EventDate $d) {
                    $label = $d->getStartDate()->format('d/m/Y').' → '.$d->getEndDate()->format('d/m/Y');
                    if (method_exists($d, 'isIsActive') ? $d->isIsActive() : (method_exists($d, 'isActive') ? $d->isActive() : false)) {
                        $label .= ' • Active';
                    }
                    return $label;
                },
                'label' => 'Date du forum',
            ])
            ->add('startTime', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Heure début',
            ])
            ->add('endTime', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Heure fin',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Discours' => 'Discours',
                    'Panel' => 'Panel',
                    'Pause' => 'Pause',
                    'Cérémonie' => 'Cérémonie',
                    'Signature' => 'Signature',
                    'Autre' => 'Autre',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => "Ex: Cérémonie d'ouverture"],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => "Détails (optionnel)…",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
