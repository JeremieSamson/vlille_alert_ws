<?php

namespace AppBundle\Controller\Base;

use AppBundle\Repository\AlertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class BaseController extends Controller
{
    /**
     * @return AlertRepository
     */
    public function getAlertRepository(){
        return $this->getManager()->getRepository('AppBundle:Alert');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getManager(){
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return Translator
     */
    protected function getTranslator() {
        return $this->get('translator');
    }
}