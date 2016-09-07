<?php

namespace AppBundle\Command;

use AppBundle\Entity\Station;
use AppBundle\Model\Marker;
use AppBundle\Service\VlilleGrabber;
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
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:stations')
            ->setDescription('Initialise all stations IDs and geolocalisation')
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

        /** @var VlilleGrabber $grabber */
        $grabber = $this->getContainer()->get('vlille.grabber');

        /** @var ArrayCollection $stations */
        $markers = $grabber->getAllStationsFromVlilleAPI();

        // Starting progress
        $output->writeln('<comment>Starting synchronisation ...</comment>');
        $progress = new ProgressBar($output, count($markers));
        $progress->start();

        /** @var Marker $marker */
        foreach($markers as $marker) {
            $date = new \DateTime();

            $station_db = $em->getRepository('AppBundle:Station')->findOneBy(array("stationid" => $marker->getId()));

            $station = (!$station_db) ? new Station() : $station_db;

            $station->setLastupd($date);
            $station->setName($marker->getName());
            $station->setLat($marker->getLat());
            $station->setLng($marker->getLng());

            if (!$station_db) {
                $station->setStationid($marker->getId());

                $em->persist($station);
            }

            $output->writeln("<comment>" . ((!$station_db) ? "import" : "update") ." station with id " . $marker->getId() . "</comment>");

            $em->flush();

            $progress->advance(1);
        }

        // Ending the progress bar process
        $progress->finish();
    }
}