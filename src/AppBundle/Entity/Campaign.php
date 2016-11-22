<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 *
 * @ORM\Table(name="campaign")
 * @ORM\Entity()
 */
class Campaign
{

    const FUTURE = 'future';
    const PENDING = 'pending';
    const closed = 'closed';

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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string",length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="status",type="string",length=255)
     */
    private $status;

    /**
     * @var University
     * @ORM\ManyToMany(targetEntity="University",inversedBy="campaigns")
     */
    private $universities;

    /**
     * @var Evaluation
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation",mappedBy="campaign")
     */
    private $evaluations;

    /**
     * @var Session
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Session")
     */
    private $session;

    /**
     * @var int
     * @ORM\Column(name="accepted_count",type="integer",nullable=true)
     */
    private $acceptedCount;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Campaign
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->universities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Campaign
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
     * Add university
     *
     * @param \AppBundle\Entity\University $university
     *
     * @return Campaign
     */
    public function addUniversity(\AppBundle\Entity\University $university)
    {
        $this->universities[] = $university;

        return $this;
    }

    /**
     * Remove university
     *
     * @param \AppBundle\Entity\University $university
     */
    public function removeUniversity(\AppBundle\Entity\University $university)
    {
        $this->universities->removeElement($university);
    }

    /**
     * Get universities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUniversities()
    {
        return $this->universities;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Campaign
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
     * Add evaluation
     *
     * @param \AppBundle\Entity\Evaluation $evaluation
     *
     * @return Campaign
     */
    public function addEvaluation(\AppBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations[] = $evaluation;

        return $this;
    }

    /**
     * Remove evaluation
     *
     * @param \AppBundle\Entity\Evaluation $evaluation
     */
    public function removeEvaluation(\AppBundle\Entity\Evaluation $evaluation)
    {
        $this->evaluations->removeElement($evaluation);
    }

    /**
     * Get evaluations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluations()
    {
        return $this->evaluations;
    }

    /**
     * Set session
     *
     * @param \AppBundle\Entity\Session $session
     *
     * @return Campaign
     */
    public function setSession(\AppBundle\Entity\Session $session = null)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \AppBundle\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set acceptedCount
     *
     * @param integer $acceptedCount
     *
     * @return Campaign
     */
    public function setAcceptedCount($acceptedCount)
    {
        $this->acceptedCount = $acceptedCount;

        return $this;
    }

    /**
     * Get acceptedCount
     *
     * @return integer
     */
    public function getAcceptedCount()
    {
        return $this->acceptedCount;
    }
}
