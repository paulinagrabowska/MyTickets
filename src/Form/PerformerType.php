<?php

namespace App\Form;

use App\Entity\Performer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PerformerType
 * @package App\Form
 */
class PerformerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',
                  TextType::class,
                [
                    'label' => 'label.firstname',
                    'required' => true,
                    'attr' => ['max_length' => 30],
                ])
            ->add('lastname',
                TextType::class,
                [
                    'label' => 'label.lastname',
                    'required' => true,
                    'attr' => ['max_length' => 30],
                ])
            ->add('stagename',
                TextType::class,
                [
                    'label' => 'label.stagename',
                    'attr' => ['max_length' => 50],
                ])
            ->add('info',
                TextareaType::class,
                [
                    'label' => 'label.info',
                    'attr' => ['max_length' => 512],
                ])
            ->add('musicgenre',
                TextType::class,
                [
                    'label' => 'label.musicgenre',
                    'attr' => ['max_length' => 45],
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Performer::class,
        ]);
    }
}
