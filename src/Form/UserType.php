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

/**
 * Class UserType
 * @package App\Form
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
            ->add('password',
                RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options'  => ['label' => 'label.password'],
                    'second_options' => ['label' => 'label.confirm_pass']
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
                    'years' => range(date('Y')-50, date('Y')-16),
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
