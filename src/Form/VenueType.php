<?php

namespace App\Form;

use App\Entity\Venue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    'label' => 'label.name',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ])
            ->add('city',
                TextType::class,
                [
                    'label' => 'label.city',
                    'required' => true,
                    'attr' => ['max_length' => 30],
                ])
            ->add('street',
                TextType::class,
                [
                    'label' => 'label.street',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ])
            ->add('streetnumber',
                NumberType::class,
                [
                    'label' => 'label.streetnumber',
                    'required' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Venue::class,
        ]);
    }
}
