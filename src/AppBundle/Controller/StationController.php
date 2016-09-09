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
 * Time: 15:22
 */

namespace AppBundle\Controller;

use AppBundle\Controller\Base\BaseController as Base;
use AppBundle\Entity\Alert;
use AppBundle\Form\Type\AlertType;
use AppBundle\Form\Handler\AlertHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;

class StationController extends Base
{
    /**
     * @Route("/user/station", name="station_list")
     */
    public function indexAction(Request $request)
    {
        $stations = $this->getStationRepository()->findAllStationsByUser($this->getUser());

        return $this->render('AppBundle:station:list.html.twig', array(
            'stations' => $stations
        ));
    }
}