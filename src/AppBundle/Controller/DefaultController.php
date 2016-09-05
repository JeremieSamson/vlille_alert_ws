<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AttachsAvailable;
use AppBundle\Entity\BikesAvailable;
use AppBundle\Entity\Station;
use AppBundle\Repository\AttachsAvailableRepository;
use AppBundle\Repository\BikesAvailableRepository;
use Doctrine\ORM\EntityManager;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var BikesAvailableRepository $bikesRepository */
        $bikesRepository = $em->getRepository('AppBundle:BikesAvailable');

        /** @var AttachsAvailableRepository $attachsRepository */
        $attachsRepository = $em->getRepository('AppBundle:AttachsAvailable');

        /** @var Station $ruedesarts */
        $ruedesarts = $em->getRepository('AppBundle:Station')->find(23);

        $start = \DateTime::createFromFormat("Y-m-d H:i:s", "2016-09-03 09:00:00");
        $end = \DateTime::createFromFormat("Y-m-d H:i:s", "2016-09-03 10:00:00");

        $bikesdata = $attachsdata = $dates = array();
        $bikes = $bikesRepository->findBikesByDate($ruedesarts, $start, $end);
        $attachs = $attachsRepository->findAttachsByDate($ruedesarts, $start, $end);

        /** @var BikesAvailable $bike */
        /*foreach($bikes as $bike){
            $bikesdata[] = $bike->getBikes();
            $dates[] = $bike->getCreatedAt()->format('Y/m/d H:i:s');
        }*/

        /** @var AttachsAvailable $attach */
        foreach($attachs as $attach){
            $attachsdata[] = $attach->getAttachs();
            $dates[] = $attach->getCreatedAt()->format('Y/m/d H:i:s');
        }

        $sellsHistory = array(
            /*array(
                "name" => "Bikes",
                "data" => $bikesdata
            ),*/
            array(
                "name" => "Attachs",
                "data" => $attachsdata
            ),

        );


        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('linechart');
        $ob->title->text('Bénéfices du 21/06/2013 au 27/06/2013');
        $ob->chart->type('column');

        $ob->yAxis->title(array('text' => "Bénéfices (millions d'euros)"));

        $ob->xAxis->title(array('text' => "Date du jours"));
        $ob->xAxis->categories($dates);

        $ob->series($sellsHistory);

        return $this->render('AppBundle::index.html.twig', array(
            'chart' => $ob
        ));
    }
}
