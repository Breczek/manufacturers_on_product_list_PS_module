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

    /**
     * @param Connection $connection  Doctrine DBAL connection (z @doctrine.dbal.default_connection)
     * @param string     $dbPrefix    Prefix tabel (z %database_prefix%)
     */
    public function __construct(
        Connection $connection,
        string $dbPrefix
    ) {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * Modyfikuje QueryBuilder siatki produktów:
     *  - dodaje JOIN do tabeli manufacturer,
     *  - dodaje SELECT z nazwą producenta jako "manufacturer_name".
     */
    public function modify(QueryBuilder $searchQueryBuilder, SearchCriteriaInterface $searchCriteria): void
    {
        // alias głównej tabeli produktów – w Product Grid to zwykle "p"
        $productAlias = 'p';
        $manufacturerTable = $this->dbPrefix . 'manufacturer';

        // LEFT JOIN, aby nie wycinać produktów bez producenta
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
