<?php

namespace AppBundle\Command;

use AppBundle\Entity\AttachsAvailable;
use AppBundle\Entity\BikesAvailable;
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

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:stations:infos')
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
        $size = count($stations);

        // Starting progress
        $output->writeln('<comment>Starting synchronisation ...</comment>');
        $progress = new ProgressBar($output, $size);
        $progress->start();

        /** @var Station $station */
        foreach($stations as $station)
        {
            $doc = new \DOMDocument();

            $url = self::URL . '?' . self::KEY . '=' . $station->getStationid();

            if (($xml = file_get_contents($url)) === false){
                $output->writeln('<error>Error fetching XML</error>');
            } else {
                $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

                if ($doc->loadXML($xml)) {
                    /** @var \DOMNodeList $stations_xml */
                    $stations_xml = $doc->getElementsByTagName('station');

                    /** @var \DOMNode $station_xml */
                    foreach($stations_xml as $station_xml) {
                        $adress  = trim($station_xml->getElementsByTagName('adress')->item(0)->nodeValue);

                        if (!empty($adress)) {
                            $status  = $station_xml->getElementsByTagName('status')->item(0)->nodeValue;
                            $bikes   = $station_xml->getElementsByTagName('bikes')->item(0)->nodeValue;
                            $attachs = $station_xml->getElementsByTagName('attachs')->item(0)->nodeValue;
                            $paiement= $station_xml->getElementsByTagName('paiement')->item(0)->nodeValue;
                            $lastupd = $station_xml->getElementsByTagName('lastupd')->item(0)->nodeValue;
                            $lastupd = trim(str_replace('secondes', '', $lastupd));

                            $date = new \DateTime();
                            $date->modify("-$lastupd seconds");

                            $station->setAdress($adress);
                            $station->setStatus($status);

                            //Add new bikes availabilty
                            $bikeAvailability = new BikesAvailable();
                            $bikeAvailability->setBikes($bikes);
                            $station->addBike($bikeAvailability);
                            $em->persist($bikeAvailability);

                            //Add new attach availabilty
                            $attachAvailability = new AttachsAvailable();
                            $attachAvailability->setAttachs($attachs);
                            $station->addAttach($attachAvailability);
                            $em->persist($attachAvailability);

                            //Send email if no places left
                            if ($adress == "41 RUE DES ARTS" && $attachAvailability->getAttachs() == 0){
                                $this->sendEmail();
                            }

                            $paiement = ("AVEC_TPE") ? true : false;
                            $station->setPaiement($paiement);
                            $station->setLastupd($date);

                            $output->writeln("<comment>update station $adress with id " .$station->getStationid(). "</comment>");

                            $em->flush();
                        }
                    }
                }
            }
            $progress->advance(1);
        };

        // Ending the progress bar process
        $progress->finish();

    }

    /**
     * Send Email
     */
    protected function sendEmail(){
        $message = \Swift_Message::newInstance()
            ->setSubject("Il n'y a plus de place !")
            ->setFrom('noreply@vlille-alert.com')
            ->setTo('jeremie.samson76@gmail.com')
            ->setBody(
                ":'(",
                'text/html'
            )
        ;

        $this->getContainer()->get('mailer')->send($message);
    }

    /**
     * @param $id
     * @return string
     */
    protected function getUrl($id){
        return self::URL . '?' . self::KEY . '=' . $id;
    }
}