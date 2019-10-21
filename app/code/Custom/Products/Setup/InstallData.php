<?php
namespace Custom\Products\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{

       /**
        * @var \Magento\Catalog\Model\ProductFactory
        */
       protected $productFactory;

       /**
        * @param \Magento\Catalog\Model\ProductFactory
        */
       public function __construct(
           \Magento\Catalog\Model\ProductFactory  $productFactory
       )
       {
            $this->productFactory = $productFactory;
       }

       /**
        * {@inheritDoc}
        */
       public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
       {
           $installer = $setup;
           $installer->startSetup();
           /** @var \Magento\Catalog\Model\Product $simpleProduct */
           $simpleProduct = $this->productFactory->create();
           $productData = [
               'sku'                    =>  'simplesampledata',
               'name'                   =>  'simple sample product',
               'attribute_set_id'       =>  4,
               'status'                 =>  \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED, //'1' -> ENABLE '2' -> DISABLE
               'visibiity'              =>  4,
               'type_id'                =>  'simple',
               'price'                  =>  12.2,
               'stock_data'             =>  [

                   'use_config_manage_stock'    =>  0,
                   'manage_stock'               =>  1,
                   'is_in_stock'                =>  1,
                   'qty'                        =>  100

               ]
           ];
           $simpleProduct->setData($productData);
           $simpleProduct->save();
           $simpleProductId = $simpleProduct->getId();

           /** @var \Magento\Catalog\Model\Product $configurableProduct */
           $configurableProduct = $this->productFactory->create();
           $configData = [

               'sku'                    =>  'configdata',
               'name'                   =>  'config sample product',
               'attribute_set_id'       =>  4,
               'status'                 =>  \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED, //'1' -> ENABLE '2' -> DISABLE
               'visibiity'              =>  4,
               'type_id'                =>  'simple',
               'price'                  =>  0,
               'category_ids'           =>  2,
               'stock_data'             =>  [

                   'use_config_manage_stock'    =>  0, //use config settings checkbox
                   'manage_stock'               =>  1,
                   'is_in_stock'                =>  1,
               ]
           ];
           //set color config attributes below
           $colorId = $configurableProduct->getResource()->getAttribute('color')->getID();
           $configurableProduct->getTypeInstance()->setUsedProductAttributeIds(array($colorId), $configurableProduct); //attribute ID of attribute 'size_general' in my stor
           $configurableAttributesData = $configurableProduct->getTypeInstance()->getConfigurableAttributesAsArray($configurableProduct);
           $configurableProduct->setCanSaveConfigurableAttributes(true);
           $configurableProduct->setConfigurableAttributesData($configurableAttributesData);
           $configurableProductsData = array();
           $configurableProductsData[$simpleProductId] = array( // id of a simple product associated with the configurable
               '0' => array(
                   'label'          => 'Magento Orange!', //attribute label
                   'attribute_id'   => $colorId, //color attribute id
                   'value_index'    => '193',
                   'is_percent'     => 0,
                   'pricing_value'  => '10',

               )
           );
           $configurableProduct->setConfigurableProductsData($configurableProductsData);
           $configurableProduct->save();
           $configurableProductId = $configurableProduct->getId();
           //Assign simple products to the configurable product in Magento 2
           $configurableProduct = $this->productFactory->create()->load($configurableProductId); // Load Configurable Product
           //set only one simple products into congigurable product
           $simpleProductIds = array($simpleProductId);
           $configurableProduct->setAssociatedProductIds($simpleProductIds); // Assign simple product id
           $configurableProduct->setCanSaveConfigurableAttributes(true);
           $configurableProduct->save();

           $installer->endSetup();
       }
}