<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 22/11/2016
 * Time: 17:40
 */

namespace AppBundle\Service;


use AppBundle\Entity\Candidate;
use AppBundle\Entity\Quiz\CandidateQuiz;
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

    public function startQuiz(CandidateQuiz $quiz){

        if ($quiz->getStatus() == 'waiting'){

            $allQuestions = $quiz->getQuiz()->getQuestions();

            $keys = $allQuestions->getKeys();
            var_dump($keys);

            $q = $allQuestions->get(2);

            $allQuestions->remove(2);
            $keys = $allQuestions->getKeys();
            var_dump($keys);

            $i=1;
            while ($i <= $quiz->getQuiz()->getQuestionsNumber() && $allQuestions->count()>0){

                $keys = $allQuestions->getKeys();


                $i++;
            }


            $quiz->setStartDate(new \DateTime('NOW'));
        }

    }

    public function finishQuiz(CandidateQuiz $quiz){

    }

    public function remainingTimeForQuiz(CandidateQuiz $quiz){

    }

    public function checkQuizIsFinished(CandidateQuiz $quiz){

    }

    public function goToNextQuestion(CandidateQuiz $quiz){

    }

    public function goToPreviousQuestion(CandidateQuiz $quiz){

    }

    public function getCurrentQuestion(CandidateQuiz $quiz){

    }

}