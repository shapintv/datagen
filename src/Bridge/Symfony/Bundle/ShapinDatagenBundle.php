<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle;

use Shapin\Datagen\Bridge\Symfony\Bundle\DependencyInjection\Compiler\DBALTablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ShapinDatagenBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DBALTablePass());
    }
}
