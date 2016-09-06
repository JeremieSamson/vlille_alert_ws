<?php

namespace AppBundle\Form\Handler\Base;

use CECBundle\Manager\CE2Manager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;

class FormHandler
{
    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * Form
     *
     * @var FormInterface
     */
    protected $form;

    /**
     * ObjectManager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Translator
     *
     * @var Translator
     */
    protected $translator;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param ObjectManager $em
     * @param null          $translator
     */
    public function __construct(Request $request, FormInterface $form, ObjectManager $em, $translator = null, User $user)
    {
        $this->request    = $request;
        $this->form       = $form;
        $this->em         = $em;
        $this->translator = $translator;
        $this->user       = $user;
    }

    /**
     * Translate a message
     *
     * @param $key
     *
     * @return string
     */
    public function trans($key){
        if (!$this->translator){
            throw $this->createTranslatorMissingException();
        }

        return $this->translator->trans($key);
    }

    /**
     * Returns a TranslatorMissingException.
     *
     * This will result in a 500 response code. Usage example:
     *
     * throw $this->createTranslatorMissingException();
     *
     * @return TranslatorMissingException
     */
    public function createTranslatorMissingException()
    {
        return new TranslatorMissingException($this->trans('error.translator'));
    }
}