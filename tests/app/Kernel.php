<?php

declare(strict_types=1);

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Shapin\Datagen\Bridge\Symfony\Bundle\ShapinDatagenBundle(),
            new Shapin\Datagen\Tests\Fixtures\TestBundle\TestBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getProjectDir()
    {
        $r = new \ReflectionObject($this);

        return dirname($r->getFileName());
    }
}
