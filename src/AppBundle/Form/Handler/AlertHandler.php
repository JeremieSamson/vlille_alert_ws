<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\Alert;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Form\Handler\Base\FormHandler;
use AppBundle\Form\Handler\Base\FormHandlerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;

/**
 * Handler Alert form
 */
class AlertHandler extends FormHandler implements FormHandlerInterface
{
    /**
     * Constructor
     *
     * @param Request         $request
     * @param FormInterface   $form
     * @param ObjectManager   $em
     * @param Translator|null $translator
     * @param User            $user
     */
    public function __construct(Request $request, FormInterface $form, ObjectManager $em, $translator = null, User $user){
        parent::__construct($request, $form, $em, $translator, $user);
    }

    /**
     * Process chantier form
     *
     * @return bool
     */
    public function process()
    {
        if ($this->request->isMethod('POST') || $this->request->isMethod('PUT')) {
            $this->form->handleRequest($this->request);

            if ($this->form->isValid() && $this->form->isSubmitted()) {

                $this->onSuccess();
                return true;

            }
        }

        return false;
    }

    public function onSuccess()
    {
        /** @var Alert $alert */
        $alert = $this->form->getData();

        if (!$alert->getId())
            $this->em->persist($alert);

        if (!$this->user->getAlerts()->contains($alert))
            $this->user->addAlert($alert);

        $this->em->flush();
    }
}