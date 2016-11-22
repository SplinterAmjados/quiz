<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Responsible
 *
 * @ORM\Entity()
 */
class Responsible extends  User
{

    /**
     * @var bool
     *
     * @ORM\Column(name="isAdmin", type="boolean")
     */
    private $isAdmin;

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Responsible
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function getRoles(){
        if ($this->isAdmin){
            return ['ROLE_USER','ROLE_RESPONSIBLE'];
        }else{
            return ['ROLE_USER'];
        }
    }

}
