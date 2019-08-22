<?php

namespace App\Form;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Entity\Tag;
use App\Entity\Venue;
use App\Form\DataTransformer\TagsDataTransformer;
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
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer|null
     */
    private $tagsDataTransformer = null;

    /**
     * TaskType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

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
                    'label' => 'label.limit',
                    'required' => true,
                ])
            ->add('performer',
                EntityType::class,
                [
                    'class' => Performer::class,
                    'label' => 'label.performer_stagename',
                    'required' => true,
                ])
            ->add('venue',
                EntityType::class,
                [
                    'class' => Venue::class,
                    'label' => 'label.venue_name',
                    'required' => true,
                ])
            ->add(
            'tags',
            EntityType::class,
            [
                'class' => Tag::class,
                'choice_label' => function (Tag $tag) {
                    return $tag->getTitle();
                },
                'label' => 'label.tag',
                'placeholder' => 'label.none',
                'required' => true,
                'expanded' => true,
                'multiple' =>true,
            ]
        )
        ->add(
        'tags',
        TextType::class,
        [
            'label' => 'label.tags',
            'required' => false,
            'attr' => [
                'max_length' => 255,
            ],
        ]
    );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'concert';
    }
}
