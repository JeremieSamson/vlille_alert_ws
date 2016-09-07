<?php

namespace AppBundle\Command;

use AppBundle\Entity\Alert;
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
class SyncAlertsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:sync:alerts')
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

        if ($input->getOption('verbose'))
            $output->writeln('<comment>Start : ' . $start->format('d-m-Y G:i:s') . '</comment>');

        // Importing from VLille API
        $this->sync($input, $output);

        // Showing when the script is over
        $end = new \DateTime();

        if ($input->getOption('verbose')) {
            $output->writeln("");
            $output->writeln('<comment>End : '.$end->format('d-m-Y G:i:s').'</comment>');
        }

        // Stats
        $interval = $end->diff($start);
        if ($input->getOption('verbose'))
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

        /** @var ArrayCollection $alerts */
        $alerts = $em->getRepository('AppBundle:Alert')->findAll();

        $progress = null;

        // Starting progress
        if ($input->getOption('verbose')) {
            $output->writeln('<comment>Starting synchronisation ...</comment>');
            $progress = new ProgressBar($output, count($alerts));
            $progress->start();
        }

        /** @var Alert $alert */
        foreach($alerts as $alert)
        {
            $station = $alert->getStation();
            $user    = $alert->getUser();

            /** @var \AppBundle\Model\Station $model */
            $model = $grabber->getStationFromVlilleAPI($station->getStationid());

            //Add new bikes availabilty
            $bikeAvailability = new BikesAvailable();
            $bikeAvailability->setBikes($model->getBikes());

            if (!$station->getLastBikeAvailable()){
                $station->addBike($bikeAvailability);
                $em->persist($bikeAvailability);
            } else if ($station->getLastBikeAvailable()->getBikes() != $bikeAvailability->getBikes()) {
                $station->addBike($bikeAvailability);
                $em->persist($bikeAvailability);
            }

            //Add new attach availabilty
            $attachAvailability = new AttachsAvailable();
            $attachAvailability->setAttachs($model->getAttachs());

            if (!$station->getLastAttachsAvailable()){
                $station->addAttach($attachAvailability);
                $em->persist($attachAvailability);
            } else if ($station->getLastAttachsAvailable()->getAttachs() != $attachAvailability->getAttachs()){
                $station->addAttach($attachAvailability);
                $em->persist($attachAvailability);
            }

            //Send email if no places left
            if ($attachAvailability->getAttachs() == 0){
                $this->sendEmail();

                if ($input->getOption('verbose'))
                    $output->writeln("<comment>Alert send to " .$user->getEmail(). "</comment>");
            }

            $em->flush();

            if ($input->getOption('verbose'))
                $progress->advance(1);
        };

        if ($input->getOption('verbose')) {
            // Ending the progress bar process
            $progress->finish();
        }
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
}