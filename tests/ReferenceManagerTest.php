<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests;

use PHPUnit\Framework\TestCase;
use Shapin\Datagen\Exception\DuplicateReferenceException;
use Shapin\Datagen\Exception\UnknownReferenceException;
use Shapin\Datagen\ReferenceManager;

class ReferenceManagerTest extends TestCase
{
    public function testFindAndReplace()
    {
        $data = [
            'key' => 'value',
            'table1_id' => 'REF:table1.my_awesome_row_2.id',
            'foo' => 'bar',
        ];

        $expectedData = [
            'key' => 'value',
            'table1_id' => 2,
            'foo' => 'bar',
        ];

        $this->assertSame($expectedData, $this->getReferenceManager()->findAndReplace($data));
    }

    public function testFindAndReplaceWithUnknownProperty()
    {
        $this->expectException(UnknownReferenceException::class);
        $this->expectExceptionMessage('Unable to resolve Reference "REF:table1.my_awesome_row_2.foobar".');

        $this->getReferenceManager()->findAndReplace([
            'table1_id' => 'REF:table1.my_awesome_row_2.foobar',
        ]);
    }

    public function testDuplicateReferenceThrowError()
    {
        $this->expectException(DuplicateReferenceException::class);
        $this->expectExceptionMessage('Duplicate reference "my_awesome_row_1" for fixture "table1".');

        $this->getReferenceManager()->add('table1', 'my_awesome_row_1', []);
    }

    private function getReferenceManager(): ReferenceManager
    {
        $referenceManager = new ReferenceManager();
        $referenceManager->add('table1', 'my_awesome_row_1', ['id' => 1, 'key' => 'value']);
        $referenceManager->add('table1', 'my_awesome_row_2', ['id' => 2, 'key' => 'value']);
        $referenceManager->add('table1', 'my_awesome_row_3', ['id' => 3, 'key' => 'value']);
        $referenceManager->add('table2', 'another_row', ['id' => 42, 'key' => 'value']);

        return $referenceManager;
    }
}
