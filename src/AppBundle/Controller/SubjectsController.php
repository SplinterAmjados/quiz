<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 15/11/2016
 * Time: 09:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubjectsController
 * @package AppBundle\Controller
 * @Route("/subject")
 */
class SubjectsController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/",name="subject_index")
     */
    public function indexAction(){
        $subjects = $this->getDoctrine()->getRepository("AppBundle:Project")
            ->findAll();
        return $this->render('@App/Subject/index.html.twig',array(
            'subjects' => $subjects
        ));
    }

    /**
     * @param Request $request
     * @param Project|null $project
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add_edit/{project}",name="add_edit_project")
     */
    public function addAction(Request $request,Project $project = null){

        $edit = true;
        if ($project == null){
            $edit = false;
            $project = new Project();
        }

        $form = $this->createForm(ProjectType::class,$project);

        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()){

                if (!$edit){
                    $this->getDoctrine()->getManager()->persist($project);
                }
                $this->getDoctrine()->getManager()->flush();
                if ($edit){
                    $this->addFlash('success','Sujet Modifié avec succès');
                }else{
                    $this->addFlash('success','Sujet ajouté avec succès');
                }
                return $this->redirectToRoute('subject_index');
            }
        }

        return $this->render('@App/Subject/add_edit.html.twig',array(
            'form'=> $form->createView(),
            'edit' => $edit
        ));
    }
}