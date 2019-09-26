<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Console\Helper;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ColumnDiff;
use Doctrine\DBAL\Schema\SchemaDiff;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineSchemaDiffHelper
{
    private $output;
    private $diff;

    public function __construct(OutputInterface $output, SchemaDiff $diff)
    {
        $this->output = $output;
        $this->diff = $diff;
    }

    public function render(): void
    {
        $this->renderRemovedTables();
        $this->output->writeln('');
        $this->renderNewTables();
        $this->output->writeln('');
        $this->renderChangedTables();
    }

    protected function renderRemovedTables(): void
    {
        if (0 === count($this->diff->removedTables)) {
            return;
        }

        $this->title('Removed tables:');
        $this->listing(array_map(function ($table) { return $table->getName(); }, $this->diff->removedTables));
    }

    protected function renderNewTables(): void
    {
        if (0 === count($this->diff->newTables)) {
            return;
        }

        $this->title('New tables:');
        $this->listing(array_map(function ($table) { return $table->getName(); }, $this->diff->newTables));
    }

    protected function renderChangedTables(): void
    {
        if (0 === count($this->diff->changedTables)) {
            return;
        }

        foreach ($this->diff->changedTables as $table) {
            $this->renderChangedTable($table);
        }
    }

    protected function renderChangedTable(TableDiff $tableDiff): void
    {
        $addedColumns = $tableDiff->addedColumns;
        $removedColumns = $tableDiff->removedColumns;
        $changedColumns = $this->filterChangedColumns($tableDiff->changedColumns);

        if (0 === count($addedColumns + $removedColumns + $changedColumns)) {
            return;
        }

        $this->title("Table \"{$tableDiff->name}\" have been changed:");

        foreach ($addedColumns as $column) {
            $this->renderAddedColumn($column);
        }
        foreach ($removedColumns as $column) {
            $this->renderRemovedColumn($column);
        }

        foreach ($changedColumns as $column) {
            $this->renderChangedColumn($column);
        }
    }

    protected function renderAddedColumn(Column $column): void
    {
        $this->output->writeln('<fg=green> + '.$this->formatColumn($column).'</>');
    }

    protected function renderRemovedColumn(Column $column): void
    {
        $this->output->writeln('<fg=red> - '.$this->formatColumn($column).'</>');
    }

    protected function renderChangedColumn(ColumnDiff $columnDiff): void
    {
        $this->renderRemovedColumn($columnDiff->fromColumn);
        $this->renderAddedColumn($columnDiff->column);
    }

    protected function formatColumn(Column $column): string
    {
        $formattedColumn = $column->getName().', '.$column->getType()->getName();

        $options = $this->filterOptions($column);

        if (0 < count($options)) {
            $formattedOptions = [];
            foreach ($options as $key => $value) {
                $formattedValue = is_bool($value) ? $value ? 'true' : 'false' : $value;
                $formattedOptions[] = "$key => $formattedValue";
            }

            $formattedColumn .= ', ['.implode(', ', $formattedOptions).']';
        }

        return $formattedColumn;
    }

    public function filterChangedColumns(array $changedColumns): array
    {
        $filteredChangeColumns = [];
        foreach ($changedColumns as $columnDiff) {
            if (['type'] === $columnDiff->changedProperties) {
                // Sometimes it looks like type have been changed but it's not the case
                if ($columnDiff->fromColumn->getType()->getName() === $columnDiff->column->getType()->getName()) {
                    continue;
                }
            }

            $filteredChangeColumns[] = $columnDiff;
        }

        return $filteredChangeColumns;
    }

    protected function filterOptions(Column $column): array
    {
        $options = $column->toArray();
        unset($options['name'], $options['type']);

        $referenceColumn = new Column('tmp', Type::getType($column->getType()->getName()));

        $options = array_filter($options, function ($value, $key) use (&$referenceColumn) {
            $method = 'get'.ucfirst($key);
            // Keep unknown keys
            if (!method_exists($referenceColumn, $method)) {
                return true;
            }

            return $value !== $referenceColumn->$method(); // Ignore default values
        }, ARRAY_FILTER_USE_BOTH);

        return $options;
    }

    protected function title(string $title): void
    {
        $this->output->writeln("<comment>$title</comment>");
    }

    protected function listing(array $elements): void
    {
        $elements = array_map(function ($element) {
            return sprintf(' * %s', $element);
        }, $elements);

        $this->output->writeln($elements);
    }
}
