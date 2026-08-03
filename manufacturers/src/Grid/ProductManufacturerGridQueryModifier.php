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

    public function __construct(Connection $connection, string $dbPrefix)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    public function modify(QueryBuilder $searchQueryBuilder, SearchCriteriaInterface $searchCriteria): void
    {
        // Assumes the Product Grid's base query aliases the product table as "p" —
        // not a documented contract, so this breaks silently if core ever renames it.
        $productAlias = 'p';
        $manufacturerTable = $this->dbPrefix . 'manufacturer';

        // LEFT JOIN, not INNER — a product without a manufacturer must still show up.
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
