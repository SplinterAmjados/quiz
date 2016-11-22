<?php

namespace AppBundle\Entity\Quiz;

use AppBundle\Entity\Campaign;
use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz_quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Quiz\QuizRepository")
 */
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name",type="string",length=255,nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer", nullable=true)
     */
    private $time;

    /**
     * @var int
     *
     * @ORM\Column(name="questionsNumber", type="integer", nullable=true)
     */
    private $questionsNumber;

    /**
     * @var int
     * @ORM\Column(name="quiz_session",type="integer",nullable=true)
     */
    private $quizSession;

    /**
     * @var Campaign
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campaign")
     *
     */
    private $campaign;

    /**
     * @var Question
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Quiz\Question")
     * @ORM\JoinTable(name="quiz_quiz_question")
     */
    private $questions;

    /**
     * @var QuizCandidate
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz\CandidateQuiz",mappedBy="quiz")
     */
    private $quizCandidates;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Quiz
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set questionsNumber
     *
     * @param integer $questionsNumber
     *
     * @return Quiz
     */
    public function setQuestionsNumber($questionsNumber)
    {
        $this->questionsNumber = $questionsNumber;

        return $this;
    }

    /**
     * Get questionsNumber
     *
     * @return int
     */
    public function getQuestionsNumber()
    {
        return $this->questionsNumber;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     *
     * @return Quiz
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Add question
     *
     * @param \AppBundle\Entity\Quiz\Question $question
     *
     * @return Quiz
     */
    public function addQuestion(\AppBundle\Entity\Quiz\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \AppBundle\Entity\Quiz\Question $question
     */
    public function removeQuestion(\AppBundle\Entity\Quiz\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set quizSession
     *
     * @param integer $quizSession
     *
     * @return Quiz
     */
    public function setQuizSession($quizSession)
    {
        $this->quizSession = $quizSession;

        return $this;
    }

    /**
     * Get quizSession
     *
     * @return integer
     */
    public function getQuizSession()
    {
        return $this->quizSession;
    }

    /**
     * Add quizCandidate
     *
     * @param \AppBundle\Entity\Quiz\CandidateQuiz $quizCandidate
     *
     * @return Quiz
     */
    public function addQuizCandidate(\AppBundle\Entity\Quiz\CandidateQuiz $quizCandidate)
    {
        $this->quizCandidates[] = $quizCandidate;

        return $this;
    }

    /**
     * Remove quizCandidate
     *
     * @param \AppBundle\Entity\Quiz\CandidateQuiz $quizCandidate
     */
    public function removeQuizCandidate(\AppBundle\Entity\Quiz\CandidateQuiz $quizCandidate)
    {
        $this->quizCandidates->removeElement($quizCandidate);
    }

    /**
     * Get quizCandidates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizCandidates()
    {
        return $this->quizCandidates;
    }
}
