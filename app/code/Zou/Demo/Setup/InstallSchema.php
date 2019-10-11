<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zou\Demo\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;


/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
       $this->createPhysicalStores($setup);
    }
    protected function createPhysicalStores(SchemaSetupInterface $setup)
    {
        $installer = $setup;
    
        $installer->startSetup();
    
        /**
         * Create table 'quote_donations'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('physical_stores'))
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
            )
        ->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Name'
            )
        ->addColumn(
            'url_key',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'url_key'
            )
        ->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '1k',
            [],
            'description'
            )
        ->addColumn(
            'street',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'street'
            )
        ->addColumn(
            'postcode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
             '20',
             [],
             'Postcode'
             )
       ->addColumn(
             'city',
             \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
             '100',
             [],
             'City'
            )
        ->addColumn(
            'country_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2',
            [],
            'country_code'
            )
        ->addColumn(
            'latitude',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Latitude'
            )
        ->addColumn(
            'longitude',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'longitude'
            )
        ->addColumn(
            'phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'phone'
            )
        ->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'email'
            )
        ->addColumn(
            'website',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'website'
            )
        ->addColumn(
            'opening_hours',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '1k',
            [],
            'opening_hours'
            )
        ->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => true,'default' => '0'],
            'status'
            )
        ->addColumn(
            'mage_store_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'mage_store_ids'
            )
        ->addColumn(
            'custom_field1',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'custom_field1'
            )
        ->addColumn(
            'custom_field2',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'custom_field2'
            )
        ;
        $installer->getConnection()->createTable($table);
        
        $table = $installer->getConnection()->newTable($installer->getTable('physical_store_staff'))
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
            )
        ->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'store_id'
            )
        ->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'title'
            )
        ->addColumn(
            'firstname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'firstname'
            )
        ->addColumn(
            'lastname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'lastname'
            )
        ->addColumn(
            'phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'phone'
            )
        ->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'email'
            )
        ->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'image'
            )
        ->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => true,'default' => '0'],
            'status'
        )
        ->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            '5',
            ['default'=>'0'],
            'sort_order'
            )
        ->addColumn(
            'image_holder',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            '5',
            ['default'=>'0'],
            'image_holder'
            )
        ->addForeignKey(
            $installer->getFkName('physical_store_staff', 'store_id', 'physical_stores', 'id'),
            'store_id',
            $installer->getTable('physical_stores'),
            'id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
