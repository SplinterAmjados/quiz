<?php

namespace AppBundle\Form;

use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Project;
use AppBundle\Entity\Responsible;
use AppBundle\Entity\Session;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $session = null;
        if (isset($options['session'])){
            $session = $options['session'] ;
        }

        $builder
            ->add('remoteQuizzScore',
                TextType::class,
                array('label'=>'Score Quiz 1','required'=>false)
            )
            ->add('localQuizzScore',
                TextType::class,
                array('label'=>'Score Quiz 2','required'=>false)
            )
            ->add('rhScore',
                ChoiceType::class,
                array('label'=>'Note RH',
                    'choices' =>array(
                        'A' => 3,
                        'B' => 2,
                        'C' => 1
                    ) ,
                    'required'=>false)
            )
            ->add('technicalScore',
                ChoiceType::class,
                array('label'=>'Note Techniqie',
                    'choices' =>array(
                        'A' => 3,
                        'B' => 2,
                        'C' => 1
                    ) ,'required'=>false)
            )
            ->add('rhComment',
                TextareaType::class,
                array('label'=>'Commentaire','required'=>false)
            )
            ->add('technicalComment',
                TextareaType::class,
                array('label'=>'Commentaire','required'=>false)
            )
            ->add('isAbsent',CheckboxType::class,array(
                'label'=> 'Candidat Absent',
                'required' => false
            ))
            ->add('rhResponsible',EntityType::class,array(
                'class'=> Responsible::class,
                'label' => 'Responsable RH',
                'required' => false,
                'choice_label' => function(Responsible $r){
                    return $r->getFullName();
                }
            ))
            ->add('technicalResponsible',EntityType::class,array(
                'class'=> Responsible::class,
                'label' => 'Responsable Technique',
                'required' => false,
                'choice_label' => function(Responsible $r){
                    return $r->getFullName();
                }
            ))
            ->add('assignTo',EntityType::class,array(
                'class' => Project::class,
                'label' => 'Sujet affecté',
                'choice_label'=> function(Project $p){
                        $assignations = $p->getAssignations()->count();
                        return $p->getTitle()." (Places : ".$p->getPlaces().", Affectations : ".$assignations.")";

                },
                'query_builder' => function(EntityRepository $er) use ($session){
                    $qb = $er->createQueryBuilder('p');
                      if ($session){
                            $qb->where('p.session = :session')
                                ->setParameter('session',$session);
                      }
                    return $qb;
                },
                'mapped' => false,
                'required' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Evaluation::class,
            'session' => Session::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_evaluation';
    }


}
