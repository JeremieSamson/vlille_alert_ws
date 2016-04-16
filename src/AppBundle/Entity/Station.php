<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Station
 *
 * @ORM\Table(name="station")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StationRepository")
 * @ExclusionPolicy("all")
 */
class Station
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255, nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $adress;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="bikes", type="integer", nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $bikes;

    /**
     * @var int
     *
     * @ORM\Column(name="attachs", type="integer", nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $attachs;

    /**
     * @var bool
     *
     * @ORM\Column(name="paiement", type="boolean", nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $paiement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastupd", type="datetime", nullable=true)
     * @Expose
     * @Groups({"default"})
     */
    private $lastupd;

    /**
     * @var int
     *
     * @ORM\Column(name="stationid", type="integer")
     * @Expose
     * @Groups({"default"})
     */
    private $stationid;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Expose
     * @Groups({"default"})
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Expose
     * @Groups({"default"})
     */
    private $lng;

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
     * Set adress
     *
     * @param string $adress
     * @return Station
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Station
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set bikes
     *
     * @param integer $bikes
     * @return Station
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
     * Set attachs
     *
     * @param integer $attachs
     * @return Station
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
     * Set paiement
     *
     * @param boolean $paiement
     * @return Station
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return boolean 
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set lastupd
     *
     * @param \DateTime $lastupd
     * @return Station
     */
    public function setLastupd($lastupd)
    {
        $this->lastupd = $lastupd;

        return $this;
    }

    /**
     * Get lastupd
     *
     * @return \DateTime 
     */
    public function getLastupd()
    {
        return $this->lastupd;
    }

    /**
     * @return int
     */
    public function getStationid()
    {
        return $this->stationid;
    }

    /**
     * @param int $stationid
     */
    public function setStationid($stationid)
    {
        $this->stationid = $stationid;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }
}
