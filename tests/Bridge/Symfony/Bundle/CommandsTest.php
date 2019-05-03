<?php

declare(strict_types=1);

namespace Bab\Datagen\Tests\Bridge\Symfony\Bundle;

use Doctrine\DBAL\Exception\TableNotFoundException;
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

        try {
            $tester->run(['command' => 'datagen:dbal:fixtures:load']);

            $this->fail('Loading fixtures without tables should fails!!');
        } catch (TableNotFoundException $e) {
        }

        $tester->run(['command' => 'datagen:dbal:schema:create']);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertContains('[OK] Schema created successfully.', $tester->getDisplay());

        $tester->run(['command' => 'datagen:dbal:fixtures:load']);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertContains('[OK] Fixtures created successfully.', $tester->getDisplay());
    }
}
