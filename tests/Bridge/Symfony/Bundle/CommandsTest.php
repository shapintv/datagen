<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Bridge\Symfony\Bundle;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandsTest extends KernelTestCase
{
    public function testExecute()
    {
        $tester = $this->getTester();

        $tester->run(['command' => 'shapin:datagen:dbal:load']);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertContains('[OK] Schema created successfully.', $tester->getDisplay());
        $this->assertContains('[OK] Fixtures created successfully.', $tester->getDisplay());
    }

    public function testExecuteSchemaOnly()
    {
        $tester = $this->getTester();

        $tester->run(['command' => 'shapin:datagen:dbal:load', '--schema-only' => null]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertContains('[OK] Schema created successfully.', $tester->getDisplay());
        $this->assertNotContains('[OK] Fixtures created successfully.', $tester->getDisplay());
    }

    public function testExecuteFixturesOnly()
    {
        $tester = $this->getTester();

        $tester->run(['command' => 'shapin:datagen:dbal:load', '--fixtures-only' => null]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertNotContains('[OK] Schema created successfully.', $tester->getDisplay());
        $this->assertContains('[OK] Fixtures created successfully.', $tester->getDisplay());
    }

    private function getTester(): ApplicationTester
    {
        self::bootKernel();

        $application = new Application(static::$kernel);
        $application->setCatchExceptions(false);
        $application->setAutoExit(false);

        return new ApplicationTester($application);
    }
}
