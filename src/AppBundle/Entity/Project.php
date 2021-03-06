<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity()
 */
class Project
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
     *
     * @ORM\Column(name="title", type="string", length=255,nullable=true)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="ref",type="string",length=255,nullable=true)
     */
    private $ref;

    /**
     * @var int
     *
     * @ORM\Column(name="places", type="integer",nullable=true)
     */
    private $places;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="technologies", type="string", length=255,nullable=true)
     */
    private $technologies;

    /**
     * @var Session
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Session")
     */
    private $session;

    /**
     * @var Affectation
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Affectation",mappedBy="project")
     */
    private $assignations;

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
     * Set places
     *
     * @param integer $places
     *
     * @return Project
     */
    public function setPlaces($places)
    {
        $this->places = $places;

        return $this;
    }

    /**
     * Get places
     *
     * @return int
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set technologies
     *
     * @param string $technologies
     *
     * @return Project
     */
    public function setTechnologies($technologies)
    {
        $this->technologies = $technologies;

        return $this;
    }

    /**
     * Get technologies
     *
     * @return string
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Project
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set session
     *
     * @param \AppBundle\Entity\Session $session
     *
     * @return Project
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
     * Constructor
     */
    public function __construct()
    {
        $this->assignations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add assignation
     *
     * @param \AppBundle\Entity\Affectation $assignation
     *
     * @return Project
     */
    public function addAssignation(\AppBundle\Entity\Affectation $assignation)
    {
        $this->assignations[] = $assignation;

        return $this;
    }

    /**
     * Remove assignation
     *
     * @param \AppBundle\Entity\Affectation $assignation
     */
    public function removeAssignation(\AppBundle\Entity\Affectation $assignation)
    {
        $this->assignations->removeElement($assignation);
    }

    /**
     * Get assignations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignations()
    {
        return $this->assignations;
    }
}
