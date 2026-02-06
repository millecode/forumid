<?php

namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Votre nom et prénom',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'exemple@domaine.dj',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => '+253 77 00 00 00',
                    'inputmode' => 'tel',
                    'autocomplete' => 'tel',
                ],
            ])
            ->add('institution', TextType::class, [
                'label' => 'Institution représentée',
                'attr' => [
                    'placeholder' => 'Ministère, entreprise, organisation',
                ],
            ])
            ->add('role', TextType::class, [
                'label' => 'Fonction',
                'attr' => [
                    'placeholder' => 'Directeur, charge de mission, etc.',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'inscription_form',
        ]);
    }
}
