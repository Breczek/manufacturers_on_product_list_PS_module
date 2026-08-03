<?php

namespace Tests\Stubs;

use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;

class StubGridDefinition implements GridDefinitionInterface
{
    private ColumnCollection $columns;

    public function __construct(ColumnCollection $columns)
    {
        $this->columns = $columns;
    }

    public function getColumns(): ColumnCollection
    {
        return $this->columns;
    }
}
