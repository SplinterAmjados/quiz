<?php

namespace AppBundle\Form\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('question',TextareaType::class,array(
            'label' => "Enoncé de la question"
        ))
            ->add('type',ChoiceType::class,array(
                'label' => 'Type',
                'choices'=> array(
                    'Choix Multiple' => 'multiple',
                    'Choix Unique' => 'unique'
                )
            ))
            ->add('image',FileType::class,array(
                'label' => 'Joindre une photo',
                'required'=>false,
                'mapped' => false
            ))
            ->add('responses',CollectionType::class,array(
                'entry_type'=> ResponseType::class,
                'label' => 'Réponses',
                'allow_add'=> true,
                'allow_delete'=> true,
                'by_reference'=>false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quiz\Question'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_quiz_question';
    }


}
