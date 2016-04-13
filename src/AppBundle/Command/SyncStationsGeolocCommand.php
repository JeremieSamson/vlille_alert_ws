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
class SyncStationsGeolocCommand extends ContainerAwareCommand
{
    const URL = "http://vlille.fr/stations/xml-stations.aspx";
    const STATIONS_NUMBER = 222;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:stations:geoloc')
            ->setDescription('Sync stations IDs and geolocalisation')
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

        // Starting progress
        $output->writeln('<comment>Starting synchronisation ...</comment>');
        $progress = new ProgressBar($output, $size);
        $progress->start();

        $doc = new \DOMDocument();

        $url = self::URL;
        $output->writeln($url);
        if (($xml = file_get_contents($url)) === false){
            $output->writeln('<error>Error fetching XML</error>');
        } else {
            $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

            if ($doc->loadXML($xml)) {
                $stations_xml = $doc->getElementsByTagName('marker');

                /** @var \DOMElement $station_xml */
                foreach($stations_xml as $station_xml) {
                    $id  = $station_xml->getAttribute('id');
                    $lat  = $station_xml->getAttribute('lat');
                    $lng   = $station_xml->getAttribute('lng');

                    $date = new \DateTime();

                    $station_db = $em->getRepository('AppBundle:Station')->findOneBy(array("stationid" => $id));

                    $station = (!$station_db) ? new Station() : $station_db;

                    $station->setLastupd($date);
                    $station->setLat($lat);
                    $station->setLng($lng);

                    if (!$station_db) {
                        $station->setStationid($id);

                        $em->persist($station);
                    }

                    $output->writeln("<comment>" . ((!$station_db) ? "import" : "update") ." station with id $id</comment>");

                    $em->flush();

                    $progress->advance(1);
                }
            }
        }

        // Ending the progress bar process
        $progress->finish();

    }
}