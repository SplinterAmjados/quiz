<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 21/11/2016
 * Time: 21:21
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Candidate;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\Quiz\CandidateQuiz;
use AppBundle\Entity\Quiz\Question;
use AppBundle\Entity\Quiz\Quiz;
use AppBundle\Entity\Quiz\QuizQuestion;
use AppBundle\Form\Quiz\QuestionType;
use AppBundle\Form\Quiz\QuizType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class QuizDefinitionController
 * @package AppBundle\Controller
 * @Route("/quiz_defintion")
 */
class QuizDefinitionController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/",name="quiz_definition_index")
     */
    public function indexAction()
    {

        $quizz = $this->getDoctrine()->getRepository('AppBundle:Quiz\Quiz')
            ->findAll();

        return $this->render("@App/QuizDefinition/quiz_list.html.twig", array(
            'quizz' => $quizz
        ));
    }

    /**
     * @param Request $request
     * @param Quiz|null $quiz
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add_edit/{quiz}",name="add_edit_quiz")
     */
    public function addEditQuizAction(Request $request, Quiz $quiz = null)
    {

        $edit = true;
        if ($quiz === null) {
            $quiz = new Quiz();
            $edit = false;
        }
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(QuizType::class, $quiz);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                if (!$edit) {
                    $em->persist($quiz);
                }

                $em->flush();
                $this->addFlash('success',"Quiz ajouté/modifié avec succès");
                return $this->redirectToRoute('quiz_definition_index');
            }
        }

        return $this->render('@App/QuizDefinition/add_edit.html.twig', array(
            'form' => $form->createView(),
            'edit' => $edit
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/questions_list",name="question_list")
     */
    public function questionsListAction(){
        $questions = $this->getDoctrine()->getRepository('AppBundle:Quiz\Question')->findAll();
        return $this->render("@App/QuizDefinition/question_list.html.twig",array(
            'questions' => $questions
        ));
    }

    /**
     * @param Request $request
     * @param Question|null $question
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add_edit_question/{question}",name="add_edit_question")
     */
    public function addEditQuestionAction(Request $request, Question $question = null)
    {
        $em = $this->getDoctrine()->getManager();

        $edit = true;
        if ($question === null) {
            $question = new Question();
            $edit = false;
            $oldResponses = [];
        }else{
            $oldResponses = $em->getRepository('AppBundle:Quiz\Response')
                ->findBy(array('question'=>$question));
        }

        $form = $this->createForm(QuestionType::class, $question);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                if (!$edit) {
                    $em->persist($question);
                }

                foreach($oldResponses as  $r){
                    $delete = true;
                    foreach($question->getResponses() as $r2){
                        if ($r2->getId() === $r->getId()){
                            $delete = false;
                        }
                    }
                    if ($delete){
                        $em->remove($r);
                    }
                }

                $em->flush();
                return $this->redirectToRoute('question_list');
            }
        }

        return $this->render('@App/QuizDefinition/add_edit_question.html.twig', array(
            'form' => $form->createView(),
            'edit' => $edit
        ));
    }

    /**
     * @param Request $request
     * @param Quiz $quiz
     * @return Response
     * @Route("/quiz_affectation/{quiz}",name="quiz_affectations")
     */
    public function affectQuizToCandidateAction(Request $request,Quiz $quiz){

        $em = $this->getDoctrine()->getManager();

        $campaignCandidates = $quiz->getCampaign()->getEvaluations()->map(function(Evaluation $e){
            return $e->getCandidate();
        });

        $quizCandidates = $quiz->getQuizCandidates()->map(function(CandidateQuiz $e){
            return $e->getCandidate();
        });

        $form = $this->createFormBuilder(array('candidates'=>$quizCandidates->toArray()))
            ->add('candidates',EntityType::class,array(
                'class' => Candidate::class,
                'choices' => $campaignCandidates->toArray(),
                'multiple' => true,
                'required' => true,
                'choice_label'=> function(Candidate $c){
                  return $c->getFullName();
                },
                'label' => "Liste des candidats"
            ))
        ->getForm();

        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $selectedCandidates = $form->get('candidates')->getData();

            $oldSelectedCandidates = $em->getRepository('AppBundle:Quiz\CandidateQuiz')
                ->findBy(array('quiz'=>$quiz));

            foreach($oldSelectedCandidates as $value){
                $exist = false;
                foreach($selectedCandidates as $sc){
                    if ($sc->getId() == $value->getCandidate()->getId()){
                        $exist = true;
                    }
                }
                if (!$exist){
                    $em->remove($value);
                }
            }
            $em->flush();

            foreach($selectedCandidates as $sc){
                $exist = false;
                foreach($oldSelectedCandidates as $oldSC){
                    if ($sc->getId() == $oldSC->getCandidate()->getId()){
                        $exist = true;
                    }
                }

                if (!$exist){
                    $newAffectation = new CandidateQuiz();
                    $newAffectation->setCandidate($sc)
                        ->setQuiz($quiz);
                    $em->persist(clone $newAffectation);
                }
            }
            $em->flush();
            return $this->redirectToRoute('quiz_definition_index');
        }

        return $this->render("@App/QuizDefinition/affectations_quiz.html.twig",array(
            'form' => $form->createView(),
            'quiz' => $quiz
        ));
    }

}