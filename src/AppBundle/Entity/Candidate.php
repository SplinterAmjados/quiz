<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Candidate
 *
 *
 * @ORM\Entity()
 */
class Candidate extends User
{

    /**
     * @var string
     * @ORM\Column(name="id_quiz",type="string",length=10,nullable=true,unique=true)
     */
    private $idQuiz;


    /**
     * @var University
     * @ORM\ManyToOne(targetEntity="University",inversedBy="candidates")
     */
    private $university;

    /**
     * Set university
     *
     * @param \AppBundle\Entity\University $university
     *
     * @return Candidate
     */
    public function setUniversity(\AppBundle\Entity\University $university = null)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return \AppBundle\Entity\University
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Set idQuiz
     *
     * @param string $idQuiz
     *
     * @return Candidate
     */
    public function setIdQuiz($idQuiz)
    {
        $this->idQuiz = $idQuiz;

        return $this;
    }

    /**
     * Get idQuiz
     *
     * @return string
     */
    public function getIdQuiz()
    {
        return $this->idQuiz;
    }

    public function getRoles()
    {
        return ['ROLE_CANDIDATE','ROLE_USER'];
    }

}
