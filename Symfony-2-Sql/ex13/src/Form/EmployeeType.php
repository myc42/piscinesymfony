<?php

namespace App\Form;

use App\Entity\Employee;
use App\Enum\Hours;
use App\Enum\Position;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthdate')
            ->add('active')
            ->add('employed_since')
            ->add('employed_until')
            ->add('hours', EnumType::class, [
                'class' => Hours::class,
                'choice_label' => fn (Hours $choice) => $choice->value, // Pour afficher proprement la valeur de l'enum
            ])
            ->add('position', EnumType::class, [
                'class' => Position::class,
                'choice_label' => fn (Position $choice) => $choice->value,
            ])
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getFirstname() . ' ' . $employee->getLastname();
                },
                'placeholder' => 'Aucun manager (CEO)', // Optionnel : pour laisser le choix vide si l'employé n'a pas de chef
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}