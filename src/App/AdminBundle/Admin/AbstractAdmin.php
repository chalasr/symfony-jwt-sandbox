<?php

namespace App\AdminBundle\Admin;

use App\Util\DependencyInjection\InjectableTrait;
use Sonata\AdminBundle\Admin\Admin;

/**
 * Abstract Admin.
 *
 * @author Robin Chalas <rchalas@sutucompta>
 */
abstract class AbstractAdmin extends Admin
{
    use InjectableTrait;

    /**
     * Shortcut method to locate a resource.
     *
     * @param  string $resource
     *
     * @return string Resource path
     */
    protected function locate($resource)
    {
        return $this->get('kernel')->locateResource($resource);
    }

    /**
     * Get create label.
     *
     * @return string
     */
    public function getCreateLabel()
    {
        return sprintf('%s Création', $this->translator->trans($this->getClassnameLabel(), [], 'AppAdminBundle'));
    }
}
