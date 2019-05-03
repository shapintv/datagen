<?php

declare(strict_types=1);

namespace Bab\Datagen\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Bab\Datagen\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends TestCase
{
    public function testValidConfiguration()
    {
        $config = Yaml::parseFile(__DIR__.'/../../../../app/config.yml');

        $processor = new Processor();
        $processedConfig = $processor->processConfiguration(new Configuration(), [$config['datagen']]);

        $this->assertTrue(is_array($processedConfig));
    }
}
