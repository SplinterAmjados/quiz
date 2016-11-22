<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 22/11/2016
 * Time: 15:17
 */

namespace AppBundle\Controller\Candidate;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package AppBundle\Controller\Candidate
 * @Route("/candidate")
 */
class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/candidate_index",name="candidate_index")
     */
    public function indexAction(){

        $availablesQuiz = $this->get('app.candidate.quiz.service')->getAvailableQuiz($this->getUser());

        return $this->render("@App/Candidate/index.html.twig",array(
            'quiz' => $availablesQuiz
        ));
    }

}