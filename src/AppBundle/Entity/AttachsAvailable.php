<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttachsAvailable
 *
 * @ORM\Table(name="attachs_available")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttachsAvailableRepository")
 */
class AttachsAvailable
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
     * @ORM\Column(name="attachs", type="integer")
     */
    private $attachs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Station
     *
     * @ORM\ManyToOne(targetEntity="Station", inversedBy="attachs")
     */
    private $station;

    /**
     * Constructor
     */
    public function __construct(){
        $this->createdAt = new \DateTime("now");
    }

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
     * Set attachs
     *
     * @param integer $attachs
     * @return AttachsAvailable
     */
    public function setAttachs($attachs)
    {
        $this->attachs = $attachs;

        return $this;
    }

    /**
     * Get attachs
     *
     * @return integer 
     */
    public function getAttachs()
    {
        return $this->attachs;
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
     * @return AttachsAvailable
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
