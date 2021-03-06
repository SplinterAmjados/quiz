<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 22/11/2016
 * Time: 18:11
 */

namespace AppBundle\Controller\Candidate;


use AppBundle\Entity\Quiz\CandidateQuiz;
use AppBundle\Entity\Quiz\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class QuizController
 * @package AppBundle\Controller\Candidate
 * @Route("/candidate")
 */
class QuizController extends Controller
{

    /**
     * @param CandidateQuiz $quiz
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/phase_init/{quiz}",name="phase_init")
     */
    public function initQuizAction(CandidateQuiz $quiz)
    {

        //@Todo vrification

        return $this->render("@App/Candidate/Quiz/init.html.twig", array(
            'quiz' => $quiz
        ));
    }

    /**
     * @param CandidateQuiz $quiz
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/start_quiz/{quiz}",name="start_quiz")
     */
    public function startQuizAction(CandidateQuiz $quiz)
    {

        $this->get('app.candidate.quiz.service')->startQuiz($quiz);

        return $this->render("@App/Candidate/Quiz/quiz_question.html.twig", array('quiz' => $quiz));
    }

}