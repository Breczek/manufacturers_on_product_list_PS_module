<?php 
/**
 * Manufactures
 * 
 * PrestaShop 9 module using modern Hooks and services 
 * 
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
$moduleVendorAutoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($moduleVendorAutoload)) {
    require_once $moduleVendorAutoload;
}
use Breczek\Manufacturers\Grid\ProductManufacturerGridDefinitionModifier;
use Breczek\Manufacturers\Grid\ProductManufacturerGridQueryModifier;

class Manufacturers extends Module 
{
    public function __construct()
    {
        $this->name = 'manufacturers';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marcin Bręczewski';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = [
            'min' => '8.2',
            'max' => _PS_VERSION_,
        ];

        parent::__construct();

        $this->displayName = $this->l('Manufacturers on product list');
        $this->description = $this->l('The module adds a column with manufacturers in the product catalog in BO');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall manufacturers module?');

    }
        public function install()
    {
        return parent::install()
            && $this->registerHook('actionProductGridDefinitionModifier')
            && $this->registerHook('actionProductGridQueryBuilderModifier')
            && $this->registerHook('actionProductGridDataModifier');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
    public function hookActionProductGridDefinitionModifier(array $params)
    {
        /** @var ProductGridDefinitionModifier $modifier */
        $modifier = $this->get(ProductManufacturerGridDefinitionModifier::class);
        $modifier->modify($params['definition']);
    }
    public function hookActionProductGridQueryBuilderModifier(array $params)
    {
        /** @var ProductGridQueryModifier $modifier */
        $modifier = $this->get(ProductManufacturerGridQueryModifier::class);
        $modifier->modify($params['search_query_builder'], $params['search_criteria']);
    }
    public function hookActionProductGridDataModifier(array $params)
    {
        
    }
}
