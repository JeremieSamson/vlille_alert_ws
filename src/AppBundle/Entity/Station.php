<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     * @Groups({"default"})
     */
    private $name;

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
     * @var int
     *
     * @ORM\Column(name="nb_attachs", type="integer")
     * @Expose
     * @Groups({"default"})
     */
    private $nbAttachs;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AttachsAvailable", mappedBy="station")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $attachs;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BikesAvailable", mappedBy="station")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $bikes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Alert", mappedBy="station")
     */
    private $alerts;

    /**
     * Constructor
     */
    public function __construct(){
        $this->attachs = new ArrayCollection();
        $this->bikes   = new ArrayCollection();
        $this->alerts  = new ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return int
     */
    public function getNbAttachs()
    {
        return $this->nbAttachs;
    }

    /**
     * @param int $nbAttachs
     */
    public function setNbAttachs($nbAttachs)
    {
        $this->nbAttachs = $nbAttachs;
    }

    /**
     * @param BikesAvailable $bike
     *
     * @return $this
     */
    public function addBike(BikesAvailable $bike){
        $this->bikes->add($bike);

        $bike->setStation($this);

        return $this;
    }

    /**
     * @param BikesAvailable $bike
     *
     * @return $this
     */
    public function removeBike(BikesAvailable $bike){
        $this->bikes->removeElement($bike);

        return $this;
    }

    /**
     * Get bikes
     *
     * @return ArrayCollection
     */
    public function getBikes()
    {
        return $this->bikes;
    }

    /**
     * Get bikes
     *
     * @return BikesAvailable
     */
    public function getLastBikeAvailable()
    {
        return $this->bikes->last();
    }

    /**
     * @param AttachsAvailable $attach
     *
     * @return $this
     */
    public function addAttach(AttachsAvailable $attach){
        $this->attachs->add($attach);

        $attach->setStation($this);

        return $this;
    }

    /**
     * @param AttachsAvailable $attach
     *
     * @return $this
     */
    public function removeAttach(AttachsAvailable $attach){
        $this->attachs->removeElement($attach);

        return $this;
    }

    /**
     * Get attachs
     *
     * @return ArrayCollection
     */
    public function getAttachs()
    {
        return $this->attachs;
    }

    /**
     * Get bikes
     *
     * @return AttachsAvailable
     */
    public function getLastAttachsAvailable()
    {
        return $this->attachs->last();
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

    /**
     * @param Alert $alert
     *
     * @return $this
     */
    public function addAlert(Alert $alert){
        $this->alerts->add($alert);

        $alert->setStation($this);

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
    public function getAlerts(){
        return $this->alerts;
    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->getAdress();
    }
}
