<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 22/11/2016
 * Time: 17:40
 */

namespace AppBundle\Service;


use AppBundle\Entity\Candidate;
use AppBundle\Entity\Quiz\Quiz;
use Doctrine\ORM\EntityManager;

class QuizCandidateService
{
    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function getAvailableQuiz(Candidate $candidate){

        $quiz = $this->em->getRepository('AppBundle:Quiz\CandidateQuiz')
            ->findBy(array(
                'candidate' => $candidate
            ));

        return $quiz;

    }

    public function getCurrentQuiz(Candidate $candidate){

    }

    public function startQuiz(Candidate $candidate,Quiz $quiz){

    }

    public function finishQuiz(Candidate $candidate,Quiz $quiz){

    }

    public function remainingTimeForQuiz(Candidate $candidate,Quiz $quiz){

    }

    public function checkQuizIsFinished(Candidate $candidate,Quiz $quiz){

    }

    public function goToNextQuestion(Candidate $candidate,Quiz $quiz){

    }

    public function goToPreviousQuestion(Candidate $candidate,Quiz $quiz){

    }

    public function getCurrentQuestion(Candidate $candidate,Quiz $quiz){

    }

}