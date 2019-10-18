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
           $colorID = $configurableProduct->getResource()->getAttribute('color')->getID();
           


       }
}