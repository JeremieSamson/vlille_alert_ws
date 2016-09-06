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
 * Date: 11/07/16
 * Time: 12:49
 */

namespace AppBundle\Form\Handler\Base;

/**
 * TranslatorMissingException is thrown when the translator has not been initialized.
 *
 * @author Jérémie Samson <jeremie@ylly.fr>
 */
class TranslatorMissingException extends \RuntimeException
{
    public function __construct($message = 'Translator Missing.', \Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}