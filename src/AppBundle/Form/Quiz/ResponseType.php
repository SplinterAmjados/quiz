<?php

namespace AppBundle\Form\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResponseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('response', TextType::class, array('label' => ' ','constraints' => array(
            new Callback(array(
                'callback' => function($value,ExecutionContextInterface  $context){
                    if (trim($value)==''){
                        $context->addViolation("Réponse ne doit pas être vide");
                    }

                }
            ))
        )))
            ->add('correct', CheckboxType::class, array(
                'label' => ' ',
                'required' => false
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quiz\Response'
        ));
    }

}
