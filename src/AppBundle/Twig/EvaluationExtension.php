<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 13/11/2016
 * Time: 18:05
 */

namespace AppBundle\Twig;


use AppBundle\Entity\Evaluation;
use Doctrine\ORM\EntityManager;

class EvaluationExtension extends \Twig_Extension
{

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('score', array($this, 'scoreFilter')),
            new \Twig_SimpleFilter('status', array($this, 'statusFilter')),
            new \Twig_SimpleFilter('project', array($this, 'projectFilter')),
        );
    }

    public function scoreFilter($score){
        switch (intval($score)){
            case 1 : return "<span class='score score-c'>C</span>" ;
            case 2 : return "<span class='score score-b'>B</span>" ;
            case 3 : return "<span class='score score-a'>A</span>" ;
        }
        return '-';
    }

    public function statusFilter(Evaluation $evaluation){

        if ($evaluation->getStatus() == 'rejected'){
            return '<span class="status status-reject"><span class="glyphicon glyphicon-thumbs-down"></span> Non retenu</span>';
        }

        if ($evaluation->getStatus() === null){
            return '-';
        }

        if ($evaluation->getStatus() == 'accepted'){
            return '<span class="status status-ok"><span class="glyphicon glyphicon-thumbs-up"></span> Retenu</span>';
        }

        return '<span class="status status-waiting"><span class="glyphicon glyphicon-refresh"></span> En attente</span>';
    }

    public function projectFilter(Evaluation $evaluation){
        $assignation = $this->em->getRepository("AppBundle:Affectation")
            ->findOneBy(array(
                'candidate' => $evaluation->getCandidate(),
                'campaign' => $evaluation->getCampaign()
            ));

        if ($assignation){
            return "<span class='project'>(".$assignation->getProject()->getRef().")</span>";
        }

        return '';
    }
}