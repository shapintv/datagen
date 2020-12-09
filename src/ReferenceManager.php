<?php

declare(strict_types=1);

namespace Shapin\Datagen;

use Shapin\Datagen\Exception\DuplicateReferenceException;
use Shapin\Datagen\Exception\UnknownReferenceException;

class ReferenceManager
{
    private $references = [];

    public function add(string $fixture, string $name, $data): void
    {
        if (!isset($this->references[$fixture])) {
            $this->references[$fixture] = [];
        }
        if (isset($this->references[$fixture][$name])) {
            throw new DuplicateReferenceException($fixture, $name);
        }

        $this->references[$fixture][$name] = $data;
    }

    public function findAndReplace(array $data): array
    {
        $keys = array_keys($data);
        for ($i = 0; $i < \count($data); ++$i) {
            $value = $data[$keys[$i]];
            if (\is_string($value) && $this->isReference($value)) {
                $data[$keys[$i]] = $this->resolveReference($value);
            }
            if (\is_array($value)) {
                $data[$keys[$i]] = $this->findAndReplace($value);
            }
        }

        return $data;
    }

    private function isReference(string $value): bool
    {
        return 'REF:' === substr($value, 0, 4);
    }

    private function resolveReference(string $value)
    {
        $parts = explode(':', $value);

        $accessors = explode('.', $parts[1]);

        $array = $this->references;
        foreach ($accessors as $accessor) {
            if (!isset($array[$accessor])) {
                throw new UnknownReferenceException("Unable to resolve Reference \"$value\".");
            }

            $array = $array[$accessor];
        }

        return $array;
    }
}
