<?php

declare(strict_types=1);

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Bab\Datagen\Bridge\Symfony\Bundle\DatagenBundle(),
            new Bab\Datagen\Tests\Fixtures\TestBundle\TestBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getProjectDir()
    {
        return $this->getRootDir();
    }
}
