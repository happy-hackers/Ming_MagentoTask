<?php
namespace Magestore\HelloMagento\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use \Magento\Eav\Setup\EavSetupFactory;
use \Magento\Eav\Model\Config;
use \Magento\Customer\Model\ResourceModel\Attribute;
use \Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute
     */
    private $attributeResource;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     * @param Attribute $attributeResource
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        Attribute $attributeResource
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

        $eavSetup->removeAttribute(Customer::ENTITY, 'hobbies');
        $eavSetup->removeAttribute(Customer::ENTITY, 'licence');


        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'hobbies',
            [
                'type' 		   => 'varchar',
                'label'        => 'Hobbies',
                'input'        => 'select',
                'visible'      => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => false,
                'position'     => 1000,
                'user_defined' => true,
                'required' 	   => false,
                'system' => false,
                'admin_checkout' => 1,
                'default' => 'football',
                'source' => \Magestore\HelloMagento\Model\Config\Source\ModeSwitch::class,
                'option' => ['values' => ['football', 'basketball', 'Tennis']]
            ]
        );
		// link eav attribute to cusomer set
		$eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
			'hobbies');
			
        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'hobbies');
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);
        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'customer_account_create',
            'customer_account_edit'
        ]);
        $this->attributeResource->save($attribute);
    }
}