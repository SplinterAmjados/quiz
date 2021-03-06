<?php

namespace AppBundle\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="quiz_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Quiz\AnswerRepository")
 */
class Answer
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
     * @var array
     *
     * @ORM\Column(name="checkChoices", type="array", nullable=true)
     */
    private $checkChoices;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCorrect", type="boolean", nullable=true)
     */
    private $isCorrect;

    /**
     * @var Question
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz\Question")
     */
    private $question;

    /**
     * @var CandidateQuiz
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz\CandidateQuiz",inversedBy="answers")
     */
    private $candidateQuiz;

    /**
     * @var integer
     * @ORM\Column(name="answer_order",type="integer",nullable=true)
     */
    private $order;

    /**
     * @var boolean
     * @ORM\Column(name="is_answered",type="boolean",nullable=true)
     */
    private $isAnswered = false;

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
     * Set checkChoices
     *
     * @param array $checkChoices
     *
     * @return Answer
     */
    public function setCheckChoices($checkChoices)
    {
        $this->checkChoices = $checkChoices;

        return $this;
    }

    /**
     * Get checkChoices
     *
     * @return array
     */
    public function getCheckChoices()
    {
        return $this->checkChoices;
    }

    /**
     * Set isCorrect
     *
     * @param boolean $isCorrect
     *
     * @return Answer
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * Get isCorrect
     *
     * @return bool
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Quiz\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\AppBundle\Entity\Quiz\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \AppBundle\Entity\Quiz\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set candidateQuiz
     *
     * @param \AppBundle\Entity\Quiz\CandidateQuiz $candidateQuiz
     *
     * @return Answer
     */
    public function setCandidateQuiz(\AppBundle\Entity\Quiz\CandidateQuiz $candidateQuiz = null)
    {
        $this->candidateQuiz = $candidateQuiz;

        return $this;
    }

    /**
     * Get candidateQuiz
     *
     * @return \AppBundle\Entity\Quiz\CandidateQuiz
     */
    public function getCandidateQuiz()
    {
        return $this->candidateQuiz;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return Answer
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set isAnswered
     *
     * @param boolean $isAnswered
     *
     * @return Answer
     */
    public function setIsAnswered($isAnswered)
    {
        $this->isAnswered = $isAnswered;

        return $this;
    }

    /**
     * Get isAnswered
     *
     * @return boolean
     */
    public function getIsAnswered()
    {
        return $this->isAnswered;
    }
}
