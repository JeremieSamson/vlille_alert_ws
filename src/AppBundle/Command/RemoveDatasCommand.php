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
class RemoveDatasCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('vlille:remove:olddata')
            ->setDescription('Remove old data from one week')
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

        // Remove local datas
        $this->remove($input, $output);

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
    protected function remove(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $date = new \DateTime("now");
        $date->sub(new \DateInterval('P1M'));

        $olddatas = $em->getRepository('AppBundle:BikesAvailable')->findOldDataByDate($date);

        $progress = null;

        // Starting progress
        if ($input->getOption('verbose')) {
            $output->writeln('<comment>Starting removing ...</comment>');
            $progress = new ProgressBar($output, count($olddatas));
            $progress->start();
        }

        /** @var BikesAvailable $bikeAvailability */
        foreach($olddatas as $bikeAvailability)
        {
            $em->remove($bikeAvailability);

            if ($input->getOption('verbose'))
                $progress->advance(1);
        };

        if ($input->getOption('verbose')) {
            // Ending the progress bar process
            $progress->finish();
        }

        $em->flush();
    }
}