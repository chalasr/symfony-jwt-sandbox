<?php

namespace UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
  // public function prepend(ContainerBuilder $container)
  // {
  //     $kernelRootDir = $container->getParameter('kernel.root_dir');
  //     $container->prependExtensionConfig('lexik_jwt_authentication', array('private_key_path' => $kernelRootDir.'/jwt/private.pem'));
  //     $configs = $container->getExtensionConfig($this->getAlias());
  //     // use the Configuration class to generate a config array with
  //     // the settings "acme_hello"
  //     $config = $this->processConfiguration(new Configuration(), $configs);
  // }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
