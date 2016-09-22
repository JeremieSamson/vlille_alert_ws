<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Base\BaseController as Base;
use AppBundle\Entity\AttachsAvailable;
use AppBundle\Entity\BikesAvailable;
use AppBundle\Entity\Station;
use AppBundle\Repository\AttachsAvailableRepository;
use AppBundle\Repository\BikesAvailableRepository;
use AppBundle\Service\VlilleHighChart;
use Doctrine\ORM\EntityManager;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Base
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $stations = $this->getStationRepository()->findAllStationsByUser($this->getUser());

        /** @var VlilleHighChart $highchartService */
        $highchartService = $this->container->get('vlille.highchart');

        $charts = array();

        /** @var Station $station */
        foreach($stations as $station) {
            $charts[] = $highchartService->getStationAvailabilityChart($station);
        }

        return $this->render('AppBundle::index.html.twig', array(
            "charts" => $charts,
            "stations" => $stations
        ));
    }

    /**
     * @Route("/user/map", name="map")
     */
    public function mapAction(Request $request)
    {
        $stations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Station')->findAll();

        return $this->render('AppBundle:map:map.html.twig', array(
            "stations" => $stations
        ));
    }
}
