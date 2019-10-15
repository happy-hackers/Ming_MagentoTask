<?php
namespace Custom\Order\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order;

class InstallData implements InstallDataInterface
{
   
    
    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @param \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) 
    {
        $installer = $setup;

        $installer->startSetup();

        $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $installer]);
        $salesSetup -> removeAttribute(Order::ENTITY,'assignee');
        $salesSetup->addAttribute(Order::ENTITY,
         'assignee', [
            'type'      => 'varchar',
            'lable'     => 'Assignee',
            'input'     => 'text',
            'visible'      => true,
            'is_used_in_grid' => true,
            'required' 	   => false
        ]);
        $installer->getConnection()->dropColumn($installer->getTable('sales_order_grid'), 'assignee');
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'assignee',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment'   => 'assignee'
            ]
        );
        $installer->endSetup();
    }
}