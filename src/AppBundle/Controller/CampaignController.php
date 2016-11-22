<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 12/11/2016
 * Time: 18:47
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Campaign;
use AppBundle\Entity\Candidate;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\University;
use AppBundle\Form\CampaignType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class CampaignController
 * @package AppBundle\Controller
 * @Route("/campaign")
 */
class CampaignController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list",name="list_campaign")
     */
    public function listAction()
    {

        $campaigns = $this->getDoctrine()->getRepository("AppBundle:Campaign")
            ->findAll();

        return $this->render("@App/Campaign/list.html.twig", array(
            'campaigns' => $campaigns
        ));
    }

    /**
     * @param Campaign $campaign
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/evaluations/{campaign}",name="evaluations_campaign")
     */
    public function evaluationsAction(Campaign $campaign)
    {

        if ($campaign->getAcceptedCount()) {
            $evaluations = $this->getDoctrine()->getRepository("AppBundle:Evaluation")
                ->findBy(array('campaign' => $campaign), array('remoteQuizzScore' => 'DESC'), $campaign->getAcceptedCount());
        } else {
            $evaluations = $this
                ->getDoctrine()
                ->getRepository("AppBundle:Evaluation")
                ->findBy(array('campaign' => $campaign));
        }

        return $this->render("@App/Campaign/Evaluation/campaign_evaluations.html.twig",
            array('campaign' => $campaign,
                'evaluations' => $evaluations
            ));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{campaign}",name="edit_campaign")
     */
    public function editAction(Request $request, Campaign $campaign)
    {

        $form = $this->createForm(CampaignType::class, $campaign);
        $form
            ->remove('candidates')
            ->add('acceptedCount')
            ->add('quizzSession1', FileType::class, array(
                'required' => false,
                'mapped' => false
            ))
            ->add('quizzSession2', FileType::class, array(
                'required' => false,
                'mapped' => false
            ));


        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /**
                 * @var UploadedFile $quizzSession1
                 */
                $quizzSession1 = $form->get('quizzSession1')->getData();
                if ($quizzSession1 && $quizzSession1->getClientOriginalExtension() == 'csv') {
                    $this->_setQuizResult($quizzSession1, $campaign, 1);
                }

                /**
                 * @var UploadedFile $quizzSession2
                 */
                $quizzSession2 = $form->get('quizzSession2')->getData();
                if ($quizzSession2 && $quizzSession2->getClientOriginalExtension() == 'csv') {
                    $this->_setQuizResult($quizzSession2, $campaign, 2);
                }

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Campagne modifiée avec succès');
            }
        }

        return $this->render("@App/Campaign/edit.html.twig", array(
            'campaign' => $campaign,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add",name="add_campaign")
     */
    public function addAction(Request $request)
    {

        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $this->getDoctrine()->getManager()->persist($campaign);

                /**
                 * @var UploadedFile $candidateFile
                 */
                $candidateFile = $form->get('candidates')->getData();
                if ($candidateFile && $candidateFile->getClientOriginalExtension() == 'csv') {
                    $filename = md5(time()) . '.csv';
                    $filePath = $this->container->getParameter('kernel.cache_dir') . "/candidates";
                    if (!file_exists($filePath)) {
                        mkdir($filePath);
                    }
                    $candidateFile->move($filePath, $filename);

                    $file = fopen($filePath . "/" . $filename, 'r');
                    fgetcsv($file);//get headers
                    while ($line = fgetcsv($file, null, ';')) {
                        $idCandidate = $line[0];
                        $email = $line[1];
                        $lastname = $line[2];
                        $firstname = $line[3];

                        $candidate = $this->getDoctrine()->getRepository("AppBundle:Candidate")
                            ->findOneBy(array('idQuiz' => $idCandidate));

                        if (!$candidate) {
                            $candidate = new Candidate();
                            $candidate
                                ->setIdQuiz($idCandidate)
                                ->setEmail($email)
                                ->setPassword(substr(md5($candidate->getEmail()),0,6))
                                ->setFirstName($firstname)
                                ->setLastName($lastname);
                            $this->getDoctrine()->getManager()->persist($candidate);
                            $this->getDoctrine()->getManager()->flush();
                        }

                        $candidate
                            ->setEmail($email)
                            ->setFirstName($firstname)
                            ->setLastName($lastname);

                        $evaluation = new Evaluation();
                        $evaluation->setCampaign($campaign)
                            ->setCandidate($candidate);
                        $this->getDoctrine()->getManager()->persist($evaluation);
                        $this->getDoctrine()->getManager()->flush();
                    }

                }

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Campagne créée avec succès');
                return $this->redirectToRoute('evaluations_campaign', array('campaign' => $campaign->getId()));
            }
        }

        return $this->render('@App/Campaign/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     * @return BinaryFileResponse
     * @Route("/export_excel/{campaign}",name="evaluations_export_excel")
     */
    public function evaluationsExportExcelAction(Request $request, Campaign $campaign)
    {

        $status = [];
        if ($request->query->has('status')) {
            $status = explode(',', $request->query->get('status'));
        }

        $excelFile = $this->_createExcelFile($campaign, $status);

        return $excelFile;
    }

    /** ** HELPERS ** */

    /**
     * @param UploadedFile $file
     * @param Campaign $campaign
     * @param $quizType
     */
    private function _setQuizResult(UploadedFile $file, Campaign $campaign, $quizType)
    {
        $filename = md5(time()) . '.csv';
        $filePath = $this->container->getParameter('kernel.cache_dir') . "/candidates";
        if (!file_exists($filePath)) {
            mkdir($filePath);
        }
        $file->move($filePath, $filename);

        $file = fopen($filePath . "/" . $filename, 'r');
        fgetcsv($file);//get headers
        while ($line = fgetcsv($file, null, ';')) {
            $idCandidate = $line[0];
            $quiz = $line[1];
            if ($quiz === null || trim($quiz) === '') {
                $quiz = null;
            } else {
                $quiz = str_replace(',', '.', $quiz);
            }

            $candidate = $this->getDoctrine()->getRepository("AppBundle:Candidate")
                ->findOneBy(array('idQuiz' => $idCandidate));

            if (!$candidate) {
                $this->addFlash('error', "Candidat avec l'id " . $idCandidate . " n'existe pas dans le système");
            } else {
                $evaluation = $this->getDoctrine()->getRepository("AppBundle:Evaluation")
                    ->findOneBy(array('candidate' => $candidate, 'campaign' => $campaign));
                if (!$evaluation) {
                    $evaluation = new Evaluation();
                    $evaluation->setCampaign($campaign)
                        ->setCandidate($candidate);
                    $this->getDoctrine()->getManager()->persist($evaluation);
                    $this->getDoctrine()->getManager()->flush();
                }
                if ($quiz) {
                    if ($quizType === 1) {
                        $evaluation->setRemoteQuizzScore(floatval($quiz));
                    } elseif ($quizType === 2) {
                        $evaluation->setLocalQuizzScore(floatval($quiz));
                    }

                }
            }
            $this->getDoctrine()->getManager()->flush();
        }
    }

    /**
     * @param $campaign
     * @param array $status
     * @throws \PHPExcel_Exception
     * @return StreamedResponse
     */
    private function _createExcelFile(Campaign $campaign, $status = [])
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->setActiveSheetIndex(0);
        $sheet = $phpExcelObject->getActiveSheet();
        $sheet->setTitle($campaign->getName());
        $sheet->getStyleByColumnAndRow(0, 1, 10, 500)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(0, 1, 10, 1)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow(0, 1, 10, 1)->applyFromArray(
            array( 'fill' =>
                array( 'type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' =>
                    array('rgb' => '0000a0')
                ),
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF')
                )
            )
        );

        $sheet->setCellValue('A1', 'Nom & Prénom');
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->setCellValue('B1', 'Quiz 1');
        $sheet->setCellValue('C1', 'Quiz 2');
        $sheet->setCellValue('D1', 'Note RH');
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->setCellValue('E1', 'Responsable RH');
        $sheet->setCellValue('F1', 'Commentaire RH');
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->setCellValue('G1', 'Note Tech.');
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->setCellValue('H1', 'Responsable Tech.');
        $sheet->setCellValue('I1', 'Commentaire Tech.');
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(30);
        $sheet->setCellValue('J1', 'Statut');
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->setCellValue('K1', 'Sujet affecté');
        $sheet->getColumnDimension('K')->setWidth(30);

        if ($campaign->getAcceptedCount()) {
            $evaluations = $this->getDoctrine()->getRepository("AppBundle:Evaluation")
                ->findBy(array('campaign' => $campaign), array('remoteQuizzScore' => 'DESC'), $campaign->getAcceptedCount());
        } else {
            $evaluations = $this
                ->getDoctrine()
                ->getRepository("AppBundle:Evaluation")
                ->findBy(array('campaign' => $campaign));
        }
        $i = 2;
        foreach ($evaluations as $e) {

            if (count($status)>0 && !in_array($e->getStatus(),$status)){
                continue;
            }

            if ($e->getCandidate()) {
                $sheet->setCellValue("A$i", $e->getCandidate()->getFullName());
            }

            $sheet->setCellValue("B$i", $e->getRemoteQuizzScore());
            $sheet->setCellValue("C$i", $e->getLocalQuizzScore());
            $sheet->setCellValue("D$i", $this->_getLetterForNote($e->getRhScore()));
            if ($e->getRhResponsible()) {
                $sheet->setCellValue("E$i", $e->getRhResponsible()->getFullName());
            }
            $sheet->setCellValue("F$i", $e->getRhComment());
            $sheet->setCellValue("G$i", $this->_getLetterForNote($e->getTechnicalScore()));
            if ($e->getTechnicalResponsible()) {
                $sheet->setCellValue("H$i", $e->getTechnicalResponsible()->getFullName());
            }
            $sheet->setCellValue("I$i", $e->getTechnicalComment());
            $sheet->setCellValue("J$i", $this->_getStatusLabel($e->getStatus()));
            $affectation = $this->getDoctrine()->getRepository("AppBundle:Affectation")
                ->findOneBy(array(
                    'candidate' => $e->getCandidate(),
                    'campaign' => $e->getCampaign()
                ));
            if ($affectation) {
                $sheet->setCellValue("K$i", $affectation->getProject()->getRef());
            }

            if ($e->getStatus() == 'accepted'){
                $sheet->getStyleByColumnAndRow(0, $i, 10, $i)->applyFromArray(
                    array( 'fill' =>
                        array( 'type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' =>
                            array('rgb' => '9EFF9E')
                        )
                    )
                );
            }

            $i++;
        }

        $sheet->getStyleByColumnAndRow(0,2,10,$i-1)->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //Creation de la response
        $filename = md5(time()) . ".xls";
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $campaign->getName().'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }

    private function _getLetterForNote($note){
        switch($note){
            case 1 : return 'C';
            case 2 : return 'B';
            case 3 : return 'A';
        }
        return $note;
    }

    private function _getStatusLabel($label){
        switch($label){
            case 'waiting' : return 'En attente';
            case 'rejected' : return 'Non retenu';
            case 'accepted' : return 'Retenu';
        }
        return '';
    }

}