<?php

namespace App\Util\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Repository factory CompilerPass.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class RepositoryCompilerPass implements CompilerPassInterface
{
    /**
     * Process compilation of containers.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $factory = $container->findDefinition('app.doctrine.repository.factory');

        $repositories = [];
        foreach ($container->findTaggedServiceIds('app.repository') as $id => $params) {
            foreach ($params as $param) {
                $repositories[$param['class']] = $id;
                $repository = $container->findDefinition($id);
                $repository->replaceArgument(0, new Reference('doctrine.orm.default_entity_manager'));

                $definition = new Definition();
                $definition->setClass('Doctrine\ORM\Mapping\ClassMetadata');
                $definition->setFactoryService('doctrine.orm.default_entity_manager');
                $definition->setFactoryMethod('getClassMetadata');
                $definition->setArguments([$param['class']]);
                $repository->replaceArgument(1, $definition);
            }
        }
        $factory->replaceArgument(0, $repositories);

        $container->findDefinition('doctrine.orm.configuration')->addMethodCall('setRepositoryFactory', [$factory]);
    }
}
