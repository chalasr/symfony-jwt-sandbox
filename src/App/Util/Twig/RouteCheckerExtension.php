<?php

namespace App\Util\Twig;

use Symfony\Component\DependencyInjection\Container;

/**
 * Twig extension that allow to check if a route exists by name in twig.
 *
 * @author Robin Chalas
 */
class RouteCheckerExtension extends \Twig_Extension
{
    /** @var Container */
    private $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'isRoute' => new \Twig_Function_Method($this, 'isRoute'),
        );
    }

    /**
     * Checks if a route exist.
     *
     * @param [type] $name [description]
     *
     * @return bool
     */
    public function isRoute($name)
    {
        $router = $this->container->get('router');

        return null !== $router->getRouteCollection()->get($name);
    }

    public function getName()
    {
        return 'route_checker_extension';
    }
}
