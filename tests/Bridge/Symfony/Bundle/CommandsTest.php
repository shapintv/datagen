<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Bridge\Symfony\Bundle;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandsTest extends KernelTestCase
{
    public function testExecute()
    {
        $tester = $this->getTester();

        $this->getConnection()->beginTransaction();

        $tester->run(['command' => 'shapin:datagen:load']);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('[OK] Job DONE!', $tester->getDisplay());

        $this->getConnection()->rollback();
    }

    public function testExecuteSchemaOnlythenFixturesOnly()
    {
        $tester = $this->getTester();

        $this->getConnection()->beginTransaction();

        $tester->run(['command' => 'shapin:datagen:load', '--dbal-schema-only' => null]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('[OK] Job DONE!', $tester->getDisplay());

        $tester->run(['command' => 'shapin:datagen:load', '--dbal-fixtures-only' => null]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('[OK] Job DONE!', $tester->getDisplay());

        $this->getConnection()->rollback();
    }

    private function getTester(): ApplicationTester
    {
        self::bootKernel();

        $application = new Application(static::$kernel);
        $application->setCatchExceptions(false);
        $application->setAutoExit(false);

        return new ApplicationTester($application);
    }

    private function getConnection(): Connection
    {
        // static::$container is not defined with SF < 4
        $container = isset(static::$container) ? static::$container : static::$kernel->getContainer();

        return $container->get('doctrine.dbal.default_connection');
    }
}
