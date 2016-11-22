<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 13/11/2016
 * Time: 10:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Affectation;
use AppBundle\Entity\Evaluation;
use AppBundle\Form\EvaluationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EvaluationController
 * @package AppBundle\Controller
 * @Route("/campaign/evaluation")
 */
class EvaluationController extends Controller
{

    /**
     * @param Evaluation $evaluation
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/evaluate/{evaluation}",name="evaluate",options={"expose"=true})
     */
    public function evaluateAction(Request $request, Evaluation $evaluation)
    {

        $assignation = $this->getDoctrine()->getRepository("AppBundle:Affectation")
            ->findBy(array(
                'candidate' => $evaluation->getCandidate(),
                'campaign' => $evaluation->getCampaign()
            ));

        $form = $this->createForm(EvaluationType::class, $evaluation,array('session'=>$evaluation->getCampaign()->getSession()));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach($assignation as $a){
                    $this->getDoctrine()->getManager()->remove($a);
                }
                $this->getDoctrine()->getManager()->flush();
                $project = $form->get('assignTo')->getData();
                if ($project ) {
                    $newAssignation = new Affectation();
                    $newAssignation->setCampaign($evaluation->getCampaign())
                        ->setCandidate($evaluation->getCandidate())
                        ->setProject($project);
                    $this->getDoctrine()->getManager()->persist($newAssignation);
                }

                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('evaluations_campaign', array('campaign' => $evaluation->getCampaign()->getId()));
            }
        }else{
            if (count($assignation)>0) {
                $form->get('assignTo')->setData($assignation[0]->getProject());
            }
        }




        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'content' => $this->renderView("@App/Campaign/Evaluation/evaluation_content.html.twig", array(
                    'evaluation' => $form->createView()
                ))
            ));
        } else {
            return $this->render("@App/Campaign/Evaluation/evaluation.html.twig", array(
                'evaluation' => $form->createView()
            ));
        }

    }

}