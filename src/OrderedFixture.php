<?php

declare(strict_types=1);

namespace Shapin\Datagen;

interface OrderedFixture
{
    public function getOrder(): int;
}
