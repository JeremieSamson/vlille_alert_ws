<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Base\BaseController as Base;
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

class DefaultController extends Base
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $alerts = $this->getAlertRepository()->findAllAlertsByUser($this->getUser());

        return $this->render('AppBundle:alert:list.html.twig', array(
            'alerts' => $alerts
        ));
    }
}
