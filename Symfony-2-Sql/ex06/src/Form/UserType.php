<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'data' => 'admin',
            ])
            ->add('name', TextType::class, [
                'data' => 'Jean Dupont',
            ])
            ->add('email', EmailType::class, [
                'data' => 'jean@example.com',
            ])
            ->add('enable', CheckboxType::class, [
                'required' => false,
                'data' => true,
            ])
            ->add('birthdate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('2000-01-01'),
            ])
            ->add('adresse', TextareaType::class, [
                'data' => 'Paris',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
