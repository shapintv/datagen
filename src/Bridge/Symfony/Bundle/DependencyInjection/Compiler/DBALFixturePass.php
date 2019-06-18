<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use Shapin\Datagen\Loader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DBALFixturePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loader = $container->getDefinition(Loader::class);

        foreach ($container->findTaggedServiceIds('shapin_datagen.fixture') as $id => $tags) {
            $groups = [];
            foreach ($tags as $attributes) {
                if (isset($attributes['group'])) {
                    $groups[] = $attributes['group'];
                }
            }
            $loader->addMethodCall('addFixture', [new Reference($id), $groups]);
        }
    }
}
