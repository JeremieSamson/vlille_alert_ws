<?php
/**
 *
 * This file is part of the ERP package.
 *
 * (c) Jeremie Samson <jeremie.samson76@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: jerem
 * Date: 22/09/16
 * Time: 17:41
 */

namespace AppBundle\Service;

use AppBundle\Entity\BikesAvailable;
use AppBundle\Entity\Station;
use AppBundle\Repository\BikesAvailableRepository;
use Doctrine\ORM\EntityManager;
use Ob\HighchartsBundle\Highcharts\Highchart;

class VlilleHighChart
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    /**
     * @param Station $station
     *
     * @return mixed
     */
    public function getStationAvailabilityChart(Station $station){
        /** @var BikesAvailableRepository $bikesRepository */
        $bikesRepository = $this->em->getRepository('AppBundle:BikesAvailable');

        $bikesdata = $dates = array();
        $bikes = $bikesRepository->findTodayAvailability($station);

        /** @var BikesAvailable $bike */
        foreach($bikes as $bike){
            $bikesdata[] = $bike->getBikes();
            $dates[] = $bike->getCreatedAt()->format('Y/m/d H:i:s');
        }

        $sellsHistory = array(
            array(
                "name" => "Bikes",
                "data" => $bikesdata
            )
        );

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('linechart_' .$station->getId());
        $ob->title->text('Disponibilité des Vlille de la station ' .$station->getName());
        $ob->chart->type('column');

        $ob->yAxis->title(array('text' => "Nombre de vélos"));

        $ob->xAxis->title(array('text' => "Date"));
        $ob->xAxis->categories($dates);

        $ob->series($sellsHistory);

        return $ob;
    }
}