<?php

namespace AppBundle\Entity\Quiz;

use AppBundle\Entity\Candidate;
use Doctrine\ORM\Mapping as ORM;

/**
 * CandidateQuiz
 *
 * @ORM\Table(name="quiz_candidate_quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Quiz\CandidateQuizRepository")
 */
class CandidateQuiz
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
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="score", type="float", nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="returnsCount", type="integer", nullable=true)
     */
    private $returnsCount = 0;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz\Quiz",inversedBy="quizCandidates")
     */
    private $quiz;

    /**
     * @var Candidate
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Candidate")
     */
    private $candidate;

    /**
     * @var Answer
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz\Answer",mappedBy="candidateQuiz")
     */
    private $answers;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return CandidateQuiz
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return CandidateQuiz
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set score
     *
     * @param float $score
     *
     * @return CandidateQuiz
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return CandidateQuiz
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set returnsCount
     *
     * @param integer $returnsCount
     *
     * @return CandidateQuiz
     */
    public function setReturnsCount($returnsCount)
    {
        $this->returnsCount = $returnsCount;

        return $this;
    }

    /**
     * Get returnsCount
     *
     * @return int
     */
    public function getReturnsCount()
    {
        return $this->returnsCount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz\Quiz $quiz
     *
     * @return CandidateQuiz
     */
    public function setQuiz(\AppBundle\Entity\Quiz\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set candidate
     *
     * @param \AppBundle\Entity\Candidate $candidate
     *
     * @return CandidateQuiz
     */
    public function setCandidate(\AppBundle\Entity\Candidate $candidate = null)
    {
        $this->candidate = $candidate;

        return $this;
    }

    /**
     * Get candidate
     *
     * @return \AppBundle\Entity\Candidate
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Add answer
     *
     * @param \AppBundle\Entity\Quiz\Answer $answer
     *
     * @return CandidateQuiz
     */
    public function addAnswer(\AppBundle\Entity\Quiz\Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \AppBundle\Entity\Quiz\Answer $answer
     */
    public function removeAnswer(\AppBundle\Entity\Quiz\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
