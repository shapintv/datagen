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
        self::bootKernel();

        $application = new Application(static::$kernel);
        $application->setCatchExceptions(false);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'shapin:datagen:dbal:load']);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertContains('[OK] Schema created successfully.', $tester->getDisplay());
        $this->assertContains('[OK] Fixtures created successfully.', $tester->getDisplay());
    }
}
