# Manufacturers on Product List

Adds a **Manufacturer** column to the product list in the PrestaShop back office, right after the
product name — no need to open a product to see who makes it.

## What it does

Hooks into the core Product Grid (Catalog → Products) without touching any theme or core file:

- Adds a `manufacturer_name` column, positioned immediately after `name`.
- Extends the grid's underlying query with a `LEFT JOIN` to `manufacturer`, so products without a
  manufacturer still appear in the list.

## How it works

Three services, wired through `config/admin/services.yml`, hook into the Grid component
PrestaShop has used for the product list since 1.7.6 — unchanged across PS 8 and PS 9:

- `actionProductGridDefinitionModifier` → `ProductManufacturerGridDefinitionModifier` adds the
  column definition.
- `actionProductGridQueryBuilderModifier` → `ProductManufacturerGridQueryModifier` extends the
  Doctrine `QueryBuilder` with the join and select.
- `actionProductGridDataModifier` is registered but currently unused — no per-row post-processing
  is needed for a plain joined column.

## Requirements

- PrestaShop `8.2` – `9.x` (declared via `ps_versions_compliancy`)
- PHP `>=8.1`

## Installation

1. Copy `manufacturers/` to `modules/manufacturers/` in your PrestaShop installation.
2. Install it from Back Office → Modules, or via CLI.
3. Open Catalog → Products — the Manufacturer column appears after Name.

## Tests

```
composer install
vendor/bin/phpunit
```

Covers the column definition (position, label, `field` option) and the query modifier's generated
SQL (join clause, select, custom table prefix) — the latter runs against a real Doctrine DBAL
connection (SQLite, in-memory) rather than a hand-rolled stub.

## License

AFL-3.0 — see `manufacturers/composer.json`.
