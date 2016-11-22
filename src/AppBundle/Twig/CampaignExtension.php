<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 13/11/2016
 * Time: 19:31
 */

namespace AppBundle\Twig;



use AppBundle\Entity\Campaign;

class CampaignExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('registrationsCount', array($this, 'registrationsCountFilter')),
            new \Twig_SimpleFilter('acceptedCount', array($this, 'acceptedCountFilter')),
            new \Twig_SimpleFilter('waitingCount', array($this, 'waitingCountFilter'))
        );
    }

    public function registrationsCountFilter(Campaign $campaign){
        return $campaign->getEvaluations()->count();
    }

    public function acceptedCountFilter(Campaign $campaign){
        $i = 0;
        foreach($campaign->getEvaluations() as $e){
            if ($e->getStatus() == 'accepted'){
                $i++;
            }
        }
        return $i;
    }

    public function waitingCountFilter(Campaign $campaign){
        $i = 0;
        foreach($campaign->getEvaluations() as $e){
            if ($e->getStatus() == 'waiting'){
                $i++;
            }
        }
        return $i;
    }

}