<?php

namespace App\Util\DependencyInjection;

/**
 * Add DependencyInjection shortcut methods.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait InjectableTrait
{
    /**
     * Shortcut method to retrieve a service.
     *
     * @param  string $id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Shortcut method for retrieve container in Sonata admin
     * class.
     *
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
        return $this->getConfigurationPool()->getContainer();
    }
}
