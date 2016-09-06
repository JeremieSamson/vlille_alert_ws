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

class AlertController extends Base
{
    /**
     * @Route("/alert/add", name="add_alert")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request){
        $alert = new Alert();

        $form = $this->createForm(AlertType::class, $alert, array('method' => 'POST'));
        $formHandler = new AlertHandler($request, $form, $this->getManager(), $this->getTranslator(), $this->getUser());

        if ($formHandler->process()) {
            $this->addFlash("success", "L'alert a bien été ajouté");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:alert:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}