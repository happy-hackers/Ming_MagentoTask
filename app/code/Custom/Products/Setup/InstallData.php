<?php
namespace Custom\Products\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Custom\Products\Helper\AreaCode;

class InstallData implements InstallDataInterface
{

       /**
        * @var \Magento\Catalog\Model\ProductFactory
        */
       protected $productFactory;

       /**
        * @var AreaCode
        */
       protected $areaCode;

        /**
         * InstallData constructor.
         * @param \Magento\Catalog\Model\ProductFactory $productFactory
         * @param AreaCode $areaCode
         */
       public function __construct(
           \Magento\Catalog\Model\ProductFactory  $productFactory,
           AreaCode $areaCode
       )
       {
            $this->areaCode = $areaCode;
            $this->productFactory = $productFactory;

       }

       /**
        * {@inheritDoc}
        */
       public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
       {
           $installer = $setup;
           $installer->startSetup();
           //set areacode
           $this->areaCode->execute();
           /** @var \Magento\Catalog\Model\Product $simpleProduct */

           $simpleProduct = $this->productFactory->create();
           $productData = [
               'sku'                    =>  'testproduct10',
               'name'                   =>  'simpleproduct1',
               'attribute_set_id'       =>  4,
               'status'                 =>  \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED, //'1' -> ENABLE '2' -> DISABLE
               'visibiity'              =>  4,
               'type_id'                =>  'simple',
               'price'                  =>  12.232,
               'stock_data'             =>  [

                   'use_config_manage_stock'    =>  0,
                   'manage_stock'               =>  1,
                   'is_in_stock'                =>  1,
                   'qty'                        =>  100

               ]
           ];
           $simpleProduct->setData($productData);
           $simpleProduct->save();
           $installer->endSetup();
       }
}