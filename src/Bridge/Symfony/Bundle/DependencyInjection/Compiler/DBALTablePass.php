<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use Shapin\Datagen\DBAL\Loader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DBALTablePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loader = $container->getDefinition(Loader::class);

        foreach ($container->findTaggedServiceIds('shapin_datagen.dbal_table') as $id => $tags) {
            $groups = [];
            foreach ($tags as $attributes) {
                if (isset($attributes['group'])) {
                    $groups[] = $attributes['group'];
                }
            }
            $loader->addMethodCall('addTable', [new Reference($id), $groups]);
        }
    }
}
