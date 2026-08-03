<?php

namespace Tests\Unit;

use Breczek\Manufacturers\Grid\ProductManufacturerGridDefinitionModifier;
use PHPUnit\Framework\TestCase;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;
use Tests\Stubs\StubGridDefinition;

final class ProductManufacturerGridDefinitionModifierTest extends TestCase
{
    public function testManufacturerColumnIsInsertedRightAfterName(): void
    {
        $columns = new ColumnCollection();
        $columns->add(new DataColumn('id_product'));
        $columns->add(new DataColumn('name'));
        $columns->add(new DataColumn('reference'));

        (new ProductManufacturerGridDefinitionModifier())->modify(new StubGridDefinition($columns));

        $this->assertSame(
            ['id_product', 'name', 'manufacturer_name', 'reference'],
            $columns->ids()
        );
    }

    public function testManufacturerColumnHasExpectedLabelAndFieldOption(): void
    {
        $columns = new ColumnCollection();
        $columns->add(new DataColumn('name'));

        (new ProductManufacturerGridDefinitionModifier())->modify(new StubGridDefinition($columns));

        $added = $this->findColumn($columns, 'manufacturer_name');

        $this->assertNotNull($added);
        $this->assertSame('Manufacturer', $added->getName());
        $this->assertSame(['field' => 'manufacturer_name'], $added->getOptions());
    }

    public function testColumnIsAppendedWhenNameColumnIsMissing(): void
    {
        // addAfter() falls back to appending when the anchor column isn't found —
        // this locks in that fallback rather than a silent no-op or an exception.
        $columns = new ColumnCollection();
        $columns->add(new DataColumn('id_product'));

        (new ProductManufacturerGridDefinitionModifier())->modify(new StubGridDefinition($columns));

        $this->assertSame(['id_product', 'manufacturer_name'], $columns->ids());
    }

    private function findColumn(ColumnCollection $columns, string $id): ?DataColumn
    {
        foreach ($columns as $column) {
            if ($column->getId() === $id) {
                return $column;
            }
        }

        return null;
    }
}
