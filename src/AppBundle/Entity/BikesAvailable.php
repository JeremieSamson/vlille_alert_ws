<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BikesAvailable
 *
 * @ORM\Table(name="bikes_available")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BikesAvailableRepository")
 */
class BikesAvailable
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
     * @var int
     *
     * @ORM\Column(name="bikes", type="integer")
     */
    private $bikes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Station
     *
     * @ORM\ManyToOne(targetEntity="Station", inversedBy="bikes")
     */
    private $station;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct(){
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Set bikes
     *
     * @param integer $bikes
     * @return BikesAvailable
     */
    public function setBikes($bikes)
    {
        $this->bikes = $bikes;

        return $this;
    }

    /**
     * Get bikes
     *
     * @return integer 
     */
    public function getBikes()
    {
        return $this->bikes;
    }

    /**
     * @return Station
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * @param Station $station
     */
    public function setStation($station)
    {
        $this->station = $station;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BikesAvailable
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
