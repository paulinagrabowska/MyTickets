<?php

namespace App\Form;

use App\Entity\Concert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    'label' => 'label.name',
                    'required' => true,
                    'attr' => ['max_length' => 30],
                ])
            ->add('info',
                TextareaType::class,
                [
                    'label' => 'label.info',
                    'required' => true,
                    'attr' => ['max_length' => 512],
                ])
            ->add('date',
                DateType::class,
                [
                    'label' => 'label.date',
                    'required' => true,
                ])
            ->add('reservation_limit',
                NumberType::class,
                [
                    'label' => 'label.reservationlimit',
                    'required' => true,
                ])
            ->add('performer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
