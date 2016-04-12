<?php

namespace AppBundle\Command;

use AppBundle\Entity\Station;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: jerem
 * Date: 12/04/16
 * Time: 20:03
 */
class SyncStationsCommand extends ContainerAwareCommand
{
    const URL = "http://vlille.fr/stations/xml-station.aspx";
    const KEY = "borne";
    const STATIONS_NUMBER = 147;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:stations')
            ->setDescription('Sync with VLille API')
        ;
    }
    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $start = new \DateTime();
        $output->writeln('<comment>Start : ' . $start->format('d-m-Y G:i:s') . '</comment>');

        // Importing from VLille API
        $this->sync($input, $output);

        // Showing when the script is over
        $end = new \DateTime();
        $output->writeln("");
        $output->writeln('<comment>End : ' . $end->format('d-m-Y G:i:s') . '</comment>');

        // Stats
        $interval = $end->diff($start);
        $output->writeln('<comment>Executed in ' . $interval->format('%s secondes') . '</comment>');
    }

    protected function sync(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var ArrayCollection $stations */
        $stations = $em->getRepository('AppBundle:Station')->findAll();
        $size = (count($stations) > 0) ? count($stations) : self::STATIONS_NUMBER;

        $station_id = 1;
        $hasAdress  = false;

        // Starting progress
        $output->writeln('<comment>Starting synchronisation ...</comment>');
        $progress = new ProgressBar($output, $size);
        $progress->start();

        do {
            $doc = new \DOMDocument();

            $url = self::URL . '?' . self::KEY . '=' . $station_id;

            if (($xml = file_get_contents($url)) === false){
                $output->writeln('<error>Error fetching XML</error>');
            } else {
                $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

                if ($doc->loadXML($xml)) {
                    $stations_xml = $doc->getElementsByTagName('station');

                    foreach($stations_xml as $station_xml) {
                        $adress  = $station_xml->getElementsByTagName('adress')->item(0)->nodeValue;
                        $hasAdress = !empty($adress);

                        if ($hasAdress) {

                            $status  = $station_xml->getElementsByTagName('status')->item(0)->nodeValue;
                            $bikes   = $station_xml->getElementsByTagName('bikes')->item(0)->nodeValue;
                            $attachs = $station_xml->getElementsByTagName('attachs')->item(0)->nodeValue;
                            $paiement= $station_xml->getElementsByTagName('paiement')->item(0)->nodeValue;
                            $lastupd = $station_xml->getElementsByTagName('lastupd')->item(0)->nodeValue;
                            $lastupd = trim(str_replace('secondes', '', $lastupd));

                            $date = new \DateTime();
                            $date->modify("-$lastupd seconds");

                            $station_db = $em->getRepository('AppBundle:Station')->findOneBy(array("stationid" => $station_id));

                            $station = (!$station_db) ? new Station() : $station_db;

                            $station->setAdress($adress);
                            $station->setStatus($status);
                            $station->setBikes($bikes);
                            $station->setAttachs($attachs);
                            $station->setPaiement($paiement);
                            $station->setLastupd($date);

                            if (!$station_db) {
                                $station->setStationid($station_id);

                                $em->persist($station);
                            }

                            $output->writeln("<comment>" . ((!$station_db) ? "import" : "update") ." station $adress with id $station_id</comment>");

                            $em->flush();
                        } else {
                            $output->writeln('<error>No station with id ' . $station_id . '</error>');
                        }
                    }
                }
            }

            $progress->advance(1);

            $station_id++;
        }while($hasAdress);

        // Ending the progress bar process
        $progress->finish();

    }

    /**
     * @param $id
     * @return string
     */
    protected function getUrl($id){
        return self::URL . '?' . self::KEY . '=' . $id;
    }
}