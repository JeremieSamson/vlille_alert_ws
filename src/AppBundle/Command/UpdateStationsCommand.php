<?php

namespace AppBundle\Command;

use AppBundle\Entity\AttachsAvailable;
use AppBundle\Entity\BikesAvailable;
use AppBundle\Entity\Station;
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
class UpdateStationsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:update')
            ->setDescription('Sync all stations in database with VLille API')
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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function sync(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var VlilleGrabber $grabber */
        $grabber = $this->getContainer()->get('vlille.grabber');

        /** @var ArrayCollection $stations */
        $stations = $em->getRepository('AppBundle:Station')->findAll();

        // Starting progress
        $output->writeln('<comment>Starting synchronisation ...</comment>');
        $progress = new ProgressBar($output, count($stations));
        $progress->start();

        /** @var Station $station */
        foreach($stations as $station)
        {
            /** @var \AppBundle\Model\Station $model */
            $model = $grabber->getStationFromVlilleAPI($station->getStationid());

            $station->setAdress($model->getAdress());
            $station->setStatus($model->getStatus());

            $nbAttachs = $model->getBikes() + $model->getAttachs();
            if ($station->getNbAttachs() != $nbAttachs) {
                $station->setNbAttachs($nbAttachs);
            }

            if ($station->getBikes()->count() == 0 || $model->getBikes() != $station->getLastBikeAvailable()->getBikes()) {
                $bikesAvailabe = new BikesAvailable();
                $bikesAvailabe->setBikes($model->getBikes());
                $station->addBike($bikesAvailabe);
                $em->persist($bikesAvailabe);
            }

            $station->setPaiement($model->hasPaiement());
            $station->setLastupd($model->getLastupdAsDate());

            $output->writeln("<comment>update station ".$model->getAdress()." with id " .$station->getStationid(). "</comment>");

            $em->flush();

            $progress->advance(1);
        };

        // Ending the progress bar process
        $progress->finish();
    }
}