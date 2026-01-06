<?php
declare(strict_types=1);

namespace Breczek\Manufacturers\Grid;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class ProductManufacturerGridQueryModifier
{
    private Connection $connection;
    private string $dbPrefix;


    public function __construct(
        Connection $connection,
        string $dbPrefix
    ) {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    /** 
    * Modifies QueryBuilder product grids: 
    * - adds JOIN to the manufacturer table, 
    * - adds a SELECT with the manufacturer's name as "manufacturer_name". 
    */
    public function modify(QueryBuilder $searchQueryBuilder, SearchCriteriaInterface $searchCriteria): void
    {
        // alias of the main product table - in Product Grid it is usually "p"
        $productAlias = 'p';
        $manufacturerTable = $this->dbPrefix . 'manufacturer';

        // LEFT JOIN to avoid cutting products without a manufacturer
        $searchQueryBuilder
            ->leftJoin(
                $productAlias,
                $manufacturerTable,
                'm',
                'm.id_manufacturer = ' . $productAlias . '.id_manufacturer'
            )
            ->addSelect('m.name AS manufacturer_name');
    }
}
