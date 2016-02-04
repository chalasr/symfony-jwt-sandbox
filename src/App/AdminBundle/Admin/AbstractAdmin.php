<?php

namespace App\AdminBundle\Admin;

use App\Util\Controller\InjectableTrait as Injectable;
use App\Util\Controller\LocalizableTrait as Localizable;
use Sonata\AdminBundle\Admin\Admin;

/**
 * Abstract Admin.
 *
 * @author Robin Chalas <rchalas@sutucompta>
 */
abstract class AbstractAdmin extends Admin
{
    use Injectable, Localizable;

    public $realLabel;

    /**
     * Shortcut method for translate a string.
     *
     * @param string      $id
     * @param array       $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    protected function translate($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Get create label.
     *
     * @return string
     */
    public function getCreateLabel()
    {
        $createLabel = $this->translate('create_block_label');
        $entityName = $this->translate($this->getClassnameLabel(), [], 'messages');

        if ($this->translator->getLocale() == 'fr') {
            return sprintf('%s %s', $createLabel, $entityName);
        }

        return sprintf('%s %s', $entityName, $createLabel);
    }
}
