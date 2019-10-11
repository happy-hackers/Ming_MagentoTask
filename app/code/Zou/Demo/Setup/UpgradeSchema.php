<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zou\Demo\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            /*
            $this->addNewField($setup, "physical_stores", "website", array(
                "type" => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                "nullable" => true,
                "length" => 255,
                "comment" => "Physical store's website URL",
                "after" => "email"
            ));
            */
        }
    }
    /*
    protected function changeMageWebisteIdToText($setup){
        $setup->startSetup();
        $setup->getConnection()->changeColumn(
            $setup->getTable("physical_stores"),
            "mage_website_id",
            "mage_store_ids",
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'length' => 255,
                'comment' => 'Magento Store Ids'
            ]
        );
        $setup->endSetup();
    }
    */
    protected function addNewField($setup, $table, $newField, $options){
        $setup->startSetup();
        $setup->getConnection()->addColumn(
            $setup->getTable($table),
            $newField,
            $options
        );
        $setup->endSetup();
    }
    
}
