<?php

namespace AppBundle\Form;

use AppBundle\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('ref',TextType::class,array(
                'label'=> 'Réf'
            ))
            ->add('places',NumberType::class,array(
                'label' => 'Nombre de places'
            ))
            ->add('description',TextareaType::class,array(
                'required'=>false,
                'label' => 'Description'
            ))
            ->add('technologies',TextType::class,array(
                'required'=>false,
                'label' => 'Technologies'
            ))
            ->add('session',EntityType::class,array(
                'label' => 'Session',
                'class' => Session::class,
                'choice_label' => 'name'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_project';
    }


}
