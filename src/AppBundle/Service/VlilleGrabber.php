<?php

namespace AppBundle\Service;

use AppBundle\Model\Marker;
use AppBundle\Model\Station;
use Doctrine\Common\Collections\ArrayCollection;

class VlilleGrabber
{
    const URL_STATIONS = "http://vlille.fr/stations/xml-stations.aspx";
    const URL_STATION = "http://vlille.fr/stations/xml-station.aspx";
    const KEY = "borne";

    /**
     * @return ArrayCollection
     *
     * @throws \Exception
     */
    public function getAllStationsFromVlilleAPI()
    {
        $url = self::URL_STATIONS;

        $doc = new \DOMDocument();

        $stations = new ArrayCollection();

        if (($xml = file_get_contents($url)) === false) {
            throw new \Exception('Error fetching XML');
        } else {
            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

            if ($doc->loadXML($xml)) {
                $stations_xml = $doc->getElementsByTagName('marker');

                /** @var \DOMElement $station_xml */
                foreach($stations_xml as $station_xml) {
                    $id  = $station_xml->getAttribute('id');
                    $lat  = $station_xml->getAttribute('lat');
                    $lng   = $station_xml->getAttribute('lng');

                    $marker = new Marker();
                    $marker->setId($id);
                    $marker->setLat($lat);
                    $marker->setLng($lng);

                    $stations->add($marker);
                }
            }
        }

        return $stations;
    }

    /**
     * @return ArrayCollection
     *
     * @throws \Exception
     */
    public function getStationFromVlilleAPI($id)
    {
        $url = self::URL_STATION . '?' . self::KEY . '=' . $id;

        $doc = new \DOMDocument();

        $station = new Station();

        if (($xml = file_get_contents($url)) === false) {
            throw new \Exception('Error fetching XML');
        } else {
            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

            if ($doc->loadXML($xml)) {
                /** @var \DOMNode $station_xml */
                $station_xml = $doc->getElementsByTagName('station')->item(0);

                $adress  = trim($station_xml->getElementsByTagName('adress')->item(0)->nodeValue);

                if (!empty($adress)) {
                    $status  = $station_xml->getElementsByTagName('status')->item(0)->nodeValue;
                    $bikes   = $station_xml->getElementsByTagName('bikes')->item(0)->nodeValue;
                    $attachs = $station_xml->getElementsByTagName('attachs')->item(0)->nodeValue;
                    $paiement= $station_xml->getElementsByTagName('paiement')->item(0)->nodeValue;
                    $lastupd = $station_xml->getElementsByTagName('lastupd')->item(0)->nodeValue;
                    $lastupd = trim(str_replace('secondes', '', $lastupd));

                    $station->setAdress($adress);
                    $station->setStatus($status);
                    $station->setBikes($bikes);
                    $station->setAttachs($attachs);
                    $station->setPaiement($paiement);
                    $station->setLastupd($lastupd);
                }
            }
        }

        return $station;
    }
}