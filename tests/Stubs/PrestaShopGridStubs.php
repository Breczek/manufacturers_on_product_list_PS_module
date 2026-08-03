<?php

/**
 * Minimal stand-ins for the PrestaShop Core Grid interfaces this module
 * depends on — just enough surface to unit test the module's own logic,
 * not a copy of PrestaShop's real Grid implementation.
 */

namespace PrestaShop\PrestaShop\Core\Grid\Definition;

interface GridDefinitionInterface
{
    public function getColumns();
}

namespace PrestaShop\PrestaShop\Core\Grid\Column;

interface ColumnInterface
{
    public function getId(): string;
}

namespace PrestaShop\PrestaShop\Core\Grid\Column\Type\Common;

use PrestaShop\PrestaShop\Core\Grid\Column\ColumnInterface;

class DataColumn implements ColumnInterface
{
    private string $id;
    private ?string $name = null;

    /** @var array<string, mixed> */
    private array $options = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /** @param array<string, mixed> $options */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /** @return array<string, mixed> */
    public function getOptions(): array
    {
        return $this->options;
    }
}

namespace PrestaShop\PrestaShop\Core\Grid\Column;

/**
 * Ordered column list with the one operation the module under test relies
 * on — addAfter() — plus an add() convenience for building test fixtures.
 */
class ColumnCollection implements \IteratorAggregate, \Countable
{
    /** @var ColumnInterface[] */
    private array $columns = [];

    public function add(ColumnInterface $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    public function addAfter(string $columnId, ColumnInterface $column): self
    {
        $position = $this->indexOf($columnId);
        $insertAt = $position === null ? count($this->columns) : $position + 1;
        array_splice($this->columns, $insertAt, 0, [$column]);

        return $this;
    }

    private function indexOf(string $columnId): ?int
    {
        foreach ($this->columns as $index => $column) {
            if ($column->getId() === $columnId) {
                return $index;
            }
        }

        return null;
    }

    /** @return string[] */
    public function ids(): array
    {
        return array_map(static fn (ColumnInterface $column): string => $column->getId(), $this->columns);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->columns);
    }

    public function count(): int
    {
        return count($this->columns);
    }
}

namespace PrestaShop\PrestaShop\Core\Grid\Search;

interface SearchCriteriaInterface
{
}
