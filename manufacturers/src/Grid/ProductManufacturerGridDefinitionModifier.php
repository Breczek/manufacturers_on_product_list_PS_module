<?php

namespace Breczek\Manufacturers\Grid;

use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;

class ProductManufacturerGridDefinitionModifier
{
    public function modify(GridDefinitionInterface $definition): void
    {
        $columns = $definition->getColumns();

        $columns->addAfter(
            'name',
            (new DataColumn('manufacturer_name'))
                ->setName('Manufacturer') 
                ->setOptions([
                    'field' => 'manufacturer_name', 
                ])
        );
    }
}
