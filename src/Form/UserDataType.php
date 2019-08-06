<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',
                EmailType::class,
                [
                    'label' => 'label.email',
                    'required' => true,
                    'attr' => ['max_length' => 50],
                ])
            ->add('firstName',
                TextType::class,
                [
                    'label' => 'label.firstname',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ])
            ->add('lastName',
                TextType::class,
                [
                    'label' => 'label.lastname',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ])
            ->add('birthdate',
                DateType::class,
                [
                    'label' => 'label.birthdate',
                    'years' => range(date('Y')-50, date('Y')),
                    'months' => range(date('m'), 12),
                    'days' => range(date('d'), 31),
                    'required' => true,
                ]
            )
            ->add('phone',
                TelType::class,
                [
                    'label' => 'label.phone',
                    'required' => true,
                    'attr' => ['max_length' => 30],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
