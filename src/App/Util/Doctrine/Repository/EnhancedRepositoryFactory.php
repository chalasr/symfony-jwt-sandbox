<?php

namespace App\Util\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Factory for setting our EnhancedRepository in entities of REST bundles.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class EnhancedRepositoryFactory implements RepositoryFactory
{
    private $repositories;
    private $container;
    private $default;

    /**
     * Constructor.
     *
     * @param array              $repositories Registered repositories
     * @param ContainerInterface $container
     * @param RepositoryFactory  $default
     */
    public function __construct(array $repositories, ContainerInterface $container, RepositoryFactory $default)
    {
        $this->ids = $repositories;
        $this->container = $container;
        $this->default = $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        if (isset($this->ids[$entityName])) {
            return $this->container->get($this->ids[$entityName]);
        }

        $metadata = $entityManager->getClassMetadata($entityName);
        $entityNamespace = $metadata->getName();

        if (is_subclass_of($entityNamespace, '\App\Util\Doctrine\Entity\EntityInterface', true)) {
            return new EnhancedRepository($entityManager, $metadata);
        }

        return $this->default->getRepository($entityManager, $entityName);
    }
}
