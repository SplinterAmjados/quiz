<?php

namespace AppBundle\Form;

use AppBundle\Entity\Session;
use AppBundle\Entity\University;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class,array(
                'required'=>false,
                'label' => 'Date'
            ))
            ->add('name',TextType::class,array(
                'label'=> 'Nom'
            ))
            ->add('status',ChoiceType::class,array(
                'choices'=> array(
                    'closed' => 'Fermée',
                    'pending' => 'Ouverte',
                    'future' => 'Future'
                ),
                'label' => 'Statut',
                'required' => false
            ))
            ->add('universities',EntityType::class,array(
                'class' => University::class,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => 'Ecoles',
                'required' => false
            ))
            ->add('session',EntityType::class,array(
                'class' => Session::class,
                'choice_label'=> 'name',
                'label' => 'Session',
                'required' => false
            ))
            ->add('candidates',FileType::class,array(
                'required' => false,
                'mapped' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Campaign'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_campaign';
    }


}
