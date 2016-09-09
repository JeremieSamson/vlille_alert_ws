<?php

namespace UserBundle\Entity;

use AppBundle\Entity\Alert;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vlille_user")
 */

class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Alert", mappedBy="user")
     */
    private $alerts;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->alerts  = new ArrayCollection();
    }

    /**
     * @param Alert $alert
     *
     * @return $this
     */
    public function addAlert(Alert $alert){
        $this->alerts->add($alert);

        $alert->setUser($this);

        return $this;
    }

    /**
     * @param Alert $alert
     *
     * @return $this
     */
    public function removeAlert(Alert $alert){
        $this->alerts->removeElement($alert);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStations(){
        $stations = new ArrayCollection();

        /** @var Alert $alert */
        foreach($this->alerts as $alert){
            if (!$stations->contains($alert->getStation()))
                $stations->add($alert->getStation());
        }

        return $stations;
    }

    /**
     * @return ArrayCollection
     */
    public function getAlerts(){
        return $this->alerts;
    }
}