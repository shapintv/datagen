<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\DependencyInjection;

use Doctrine\DBAL\Connection;
use Shapin\Stripe\StripeClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ShapinDatagenExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (class_exists(Connection::class)) {
            $loader->load('processor_dbal.xml');
        }
        if (class_exists(StripeClient::class)) {
            $loader->load('processor_stripe.xml');
        }
    }
}
