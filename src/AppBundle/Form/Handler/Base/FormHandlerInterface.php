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
 * Date: 05/07/16
 * Time: 16:05
 */

namespace AppBundle\Form\Handler\Base;

interface FormHandlerInterface
{
    /**
     * This method validate the form method, content and analyse if the onSuccess must be called
     *
     * @return boolean
     */
    function process();

    /**
     * This method update and or persist the form object
     *
     * @return mixed
     */
    function onSuccess();
}