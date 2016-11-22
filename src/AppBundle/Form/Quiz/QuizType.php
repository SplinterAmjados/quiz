<?php

namespace AppBundle\Form\Quiz;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Quiz\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('time',IntegerType::class,array(
                'label' => 'Temps du Quiz'
            ))
            ->add('questionsNumber',IntegerType::class,array(
                'label'=> 'Nombre des Questions'
            ))
            ->add('quizSession',ChoiceType::class,array(
                'label'=> 'Session',
                'choices' => array(
                    'Session 1' => 1,
                    'Session 2' => 2,
                )
            ))
            ->add('campaign',EntityType::class,array(
                'label'=> 'Campagne',
                'placeholder'=> 'Veuillez sélectionnez une campagne',
                'class' => Campaign::class,
                'choice_label' => 'name'
            ))
            ->add('questions',EntityType::class,array(
                'class' => Question::class,
                'multiple' => true,
                'choice_label' => 'question'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quiz\Quiz'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_quiz_quiz';
    }


}
