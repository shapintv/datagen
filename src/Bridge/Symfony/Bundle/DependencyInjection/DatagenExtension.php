<?php

declare(strict_types=1);

namespace Bab\Datagen\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class DatagenExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('bab.datagen.command.dbal_schema_create');
        $definition->replaceArgument(1, $config['groups']);
        $definition = $container->getDefinition('bab.datagen.command.dbal_fixtures_load');
        $definition->replaceArgument(1, $config['groups']);
    }
}
