<?php

namespace Tests\Unit;

use Breczek\Manufacturers\Grid\ProductManufacturerGridQueryModifier;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

/**
 * Runs against a real Doctrine DBAL connection (SQLite, in-memory) rather
 * than a hand-rolled QueryBuilder stub, so the generated SQL is the actual
 * DBAL output, not an assumption about what DBAL would produce.
 */
final class ProductManufacturerGridQueryModifierTest extends TestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
    }

    private function searchCriteria(): SearchCriteriaInterface
    {
        return new class implements SearchCriteriaInterface {
        };
    }

    public function testAddsLeftJoinToManufacturerTable(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id_product')->from('ps_product', 'p');

        (new ProductManufacturerGridQueryModifier($this->connection, 'ps_'))
            ->modify($qb, $this->searchCriteria());

        $sql = $qb->getSQL();

        $this->assertStringContainsString('LEFT JOIN ps_manufacturer m', $sql);
        $this->assertStringContainsString('ON m.id_manufacturer = p.id_manufacturer', $sql);
    }

    public function testAddsManufacturerNameToSelect(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id_product')->from('ps_product', 'p');

        (new ProductManufacturerGridQueryModifier($this->connection, 'ps_'))
            ->modify($qb, $this->searchCriteria());

        $this->assertStringContainsString('m.name AS manufacturer_name', $qb->getSQL());
    }

    public function testUsesTheConfiguredDatabasePrefixForTheManufacturerTable(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id_product')->from('shop42_product', 'p');

        (new ProductManufacturerGridQueryModifier($this->connection, 'shop42_'))
            ->modify($qb, $this->searchCriteria());

        $this->assertStringContainsString('LEFT JOIN shop42_manufacturer m', $qb->getSQL());
    }

    public function testDoesNotDropExistingSelectColumns(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id_product', 'p.reference')->from('ps_product', 'p');

        (new ProductManufacturerGridQueryModifier($this->connection, 'ps_'))
            ->modify($qb, $this->searchCriteria());

        $sql = $qb->getSQL();

        $this->assertStringContainsString('p.id_product', $sql);
        $this->assertStringContainsString('p.reference', $sql);
        $this->assertStringContainsString('m.name AS manufacturer_name', $sql);
    }
}
