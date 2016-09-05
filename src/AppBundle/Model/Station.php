<?php
/**
 *
 * This file is part of the ERP package.
 *
 * (c) Jeremie Samson <jeremie.samson76@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: jerem
 * Date: 02/09/16
 * Time: 17:44
 */

namespace AppBundle\Model;

class Station
{
    const PAIEMENT = "AVEc_TPE";

    /**
     * @var string
     */
    private $adress;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var int
     */
    private $bikes;

    /**
     * @var int
     */
    private $attachs;

    /**
     * @var string
     */
    private $paiement;

    /**
     * @var string
     */
    private $lastupd;

    /**
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getBikes()
    {
        return $this->bikes;
    }

    /**
     * @param int $bikes
     */
    public function setBikes($bikes)
    {
        $this->bikes = $bikes;
    }

    /**
     * @return int
     */
    public function getAttachs()
    {
        return $this->attachs;
    }

    /**
     * @param int $attachs
     */
    public function setAttachs($attachs)
    {
        $this->attachs = $attachs;
    }

    /**
     * @return string
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * @return bool
     */
    public function hasPaiement()
    {
        return $this->paiement == $this::PAIEMENT;
    }

    /**
     * @param string $paiement
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;
    }

    /**
     * @return string
     */
    public function getLastupd()
    {
        return $this->lastupd;
    }

    /**
     * @return \DateTime
     */
    public function getLastupdAsDate(){
        $date = new \DateTime();
        $date->modify("-" . $this->getLastupd(). " seconds");

        return $date;
    }

    /**
     * @param string $lastupd
     */
    public function setLastupd($lastupd)
    {
        $this->lastupd = $lastupd;
    }
}