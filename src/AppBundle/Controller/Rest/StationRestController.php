<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Repository\StationRepository;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * Created by PhpStorm.
 * User: jerem
 * Date: 16/04/16
 * Time: 17:44
 */
class StationRestController extends FOSRestController
{
    /**
     * Retrieves the list of stations
     *
     * @ApiDoc(
     *     resource=true,
     *     statusCodes={
     *         200="Returned when successful",
     *         403="Returned when authentification failed",
     *         405="Method not allowed"
     *     },
     *     output={
     *         "class"="AppBundle\Entity\Station",
     *         "groups"={"default"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     }
     * )
     *
     * @Get("/stations")
     *
     * @return Response
     */
    public function getAccountsAction()
    {
        return $this->handleView(
            $this->view(
                $this->getStationRepository()->findAll(), 200
            )->setSerializationContext(SerializationContext::create()->setGroups(array('default')))
        );
    }

    /**
     * @return StationRepository
     */
    private function getStationRepository() {
        return $this->getDoctrine()->getRepository('AppBundle:Station');
    }
}