<?php
namespace Custom\Products\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Custom\Products\Helper\AreaCode;
class UpgradeData implements  UpgradeDataInterface{

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @param \Magento\Catalog\Model\ProductFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory  $productFactory,
        AreaCode $areaCode
    )
    {
        $areaCode->execute();
        $this->productFactory = $productFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context){
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.8', '<')) {
            $simpleProductId = 2055;
            $this->createCongifProduct($simpleProductId);
            $installer->endSetup();
        }
        if (version_compare($context->getVersion(), '1.0.9', '<')) {
            $this->createBundleProduct();
            $installer->endSetup();
        }
    }
    public function createCongifProduct($simpleProductId){
        /** @var \Magento\Catalog\Model\Product $configurableProduct */
        $configurableProduct = $this->productFactory->create();
        $configurableProduct->setSku('test-configurable1');
        $configurableProduct->setName('test name configurable1');
        $configurableProduct->setAttributeSetId(4);
        $configurableProduct->setStatus(1);
        $configurableProduct->setTypeId('configurable');
        $configurableProduct->setPrice(11);
        $configurableProduct->setWebsiteIds(array(1));
        $configurableProduct->setCategoryIds(array(31));
        $configurableProduct->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'is_in_stock' => 1, //Stock Availability
            )
        );

        $configurableProduct->getTypeInstance()->setUsedProductAttributeIds(array(152),$configurableProduct); //attribute ID of attribute 'size_general' in my store
        $configurableAttributesData = $configurableProduct->getTypeInstance()->getConfigurableAttributesAsArray($configurableProduct);
        $configurableProduct->setCanSaveConfigurableAttributes(true);
        $configurableProduct->setConfigurableAttributesData($configurableAttributesData);
        $configurableProductsData = array();

        $configurableProductsData[$simpleProductId] = array( //[$simpleProductId] = id of a simple product associated with this configurable
            '0' => array(
                'label' => 'S', //attribute label
                'attribute_id' => '152', //attribute ID of attribute 'size_general' in my store
                'value_index' => '193', //value of 'S' index of the attribute 'size_general'
                'is_percent'    => 0,
                'pricing_value' => '10',
            )
        );
        $configurableProduct->setConfigurableProductsData($configurableProductsData);
        $configurableProduct->save();
    }
    public function createBundleProduct($setup , $context){
        /** @var \Magento\Catalog\Model\Product $bundleProduct */
        $bundleProduct = $this->productFactory->create();
    }
}