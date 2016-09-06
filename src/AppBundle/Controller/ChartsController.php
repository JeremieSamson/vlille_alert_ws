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
 * Date: 06/09/16
 * Time: 14:57
 */

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

class ChartsController extends Controller
{
    /**
     * @Route("/chart", name="charts")
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

        $start = \DateTime::createFromFormat("Y-m-d H:i:s", "2016-09-05 09:00:00");
        $end = \DateTime::createFromFormat("Y-m-d H:i:s", "2016-09-05 11:00:00");

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
        $ob->title->text('Disponibilité des Vlille ' .$start->format('H:i'). ' et ' . $end->format('H:i') . ' le ' . $start->format('d/m/Y'));
        $ob->chart->type('column');

        $ob->yAxis->title(array('text' => "Nombre d'attaches disponible"));

        $ob->xAxis->title(array('text' => "Date"));
        $ob->xAxis->categories($dates);

        $ob->series($sellsHistory);

        return $this->render('AppBundle:alert:list.html.twig', array(
            'chart' => $ob
        ));
    }
}