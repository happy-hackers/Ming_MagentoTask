<?php
namespace Custom\Products\Setup;

use Magento\Catalog\Block\Adminhtml\Product\Edit\AttributeSet;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Bundle\Api\Data\OptionInterfaceFactory;
use Magento\Bundle\Api\Data\LinkInterfaceFactory;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Category;
use Magento\Store\Model\Store;
use     \Magento\Store\Model\StoreManagerInterface;
use Custom\Products\Helper\AreaCode;



class UpgradeData implements  UpgradeDataInterface {

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $productRepository;

    /**
     * @var Magento\Bundle\Api\Data\OptionInterfaceFactory
     */
    protected $optionFactory;

    /**
     * @var Magento\Bundle\Api\Data\LinkInterfaceFactory
     */
    protected $linkFactory;

    /**
     * @var Magento\Bundle\Api\Data\LinkInterfaceFactory
     */
    protected $productLinkFactory;

    /**
     * @var Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var AttributeSetFactory
     */
    protected $attributeSetFactory;

    /**
     * @var AttributeSet
     */
    protected $attributeSet;

    /**
     * @var CategoryLinkManagementInterface
     */
    protected $categoryLinkManagement;

    /**
     * @var  StoreManagerInterface
     */
    protected $storeManager;
    /**
     * UpgradeData constructor.
     * @param ProductFactory $productFactory
     * @param OptionInterfaceFactory $optionFactory
     * @param LinkInterfaceFactory $linkFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ProductLinkInterfaceFactory $ProductLinkInterfaceFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSet $attributeSet
     * @param CategorySetupFactory $categorySetupFactory
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param AreaCode $areaCode
     */
    public function __construct(
        ProductFactory  $productFactory,
        OptionInterfaceFactory $optionFactory,
        LinkInterfaceFactory   $linkFactory,
        ProductRepositoryInterface $productRepository,
        ProductLinkInterfaceFactory $ProductLinkInterfaceFactory,
        AttributeSetFactory $attributeSetFactory,
        EavSetupFactory $eavSetupFactory,
        AttributeSet $attributeSet,
        CategoryFactory $categoryFactory,
        CategoryLinkManagementInterface $categoryLinkManagement,
        StoreManagerInterface $storeManager,
        AreaCode $areaCode

    )
    {
        $this->areaCode = $areaCode;
        $this->optionFactory = $optionFactory;
        $this->linkFactory = $linkFactory;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->productLinkFactory = $ProductLinkInterfaceFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categoryFactory = $categoryFactory;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->storeManager = $storeManager;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSet = $attributeSet;



    }

    /**
     * {@inheritDoc}
     */


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context){
        $this->areaCode->execute();
        $installer = $setup;
        $installer->startSetup();

        /**
         * Enable/Change  below version  when using function
         */
//        if (version_compare($context->getVersion(), '1.0.8', '<')) {
//            $simpleProductId = 2055;
//            $this->createCongifgProduct($simpleProductId);
//        }
//        if (version_compare($context->getVersion(), '1.1.0', '<')) {
//            $this->createBundleProduct();
//        }
//        if (version_compare($context->getVersion(), '1.1.4', '<')) {
//            $this->createGroupProduct();
//        }
//        if (version_compare($context->getVersion(), '1.1.8', '<')) {
//            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
//            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
//            $this->createAttributeSet($categorySetup,$eavSetup);
//
//        }
        if (version_compare($context->getVersion(), '1.2.6','<')) {
            $this->createCategory();
        }
        $installer->endSetup();
    }
    public function createCongifgProduct($simpleProductId){
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
    public function createBundleProduct(){
        /** @var \Magento\Catalog\Model\Product $bundleProduct */
        $bundleProduct = $this->productFactory->create();
        $bundleProduct->setSku('Bundle product2');
        $bundleProduct->setName('testBundle2');
        $bundleProduct->setAttributeSetId(4);
        $bundleProduct->setStatus(1);
        $bundleProduct->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_BUNDLE);
        $bundleProduct->setPrice(118);
        $bundleProduct->setWebsiteIds(array(1));
        $bundleProduct->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'is_in_stock' => 1, //Stock Availability
            )
        );
        $bundleProduct->save();
        $bundleProduct->setBundleOptionsData(
            [
                [
                    'title' => 'Bundle Product Items1',
                    'default_title' => 'Bundle Product Items1',
                    'type' => 'select', 'required' => 1,
                    'delete' => '',
                ],
            ]
        );
        $bundleProduct->setBundleSelectionsData(
            [
                [
                    ['product_id' => 2, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '']
                ]
            ]
        );

        if ($bundleProduct->getBundleOptionsData()) {
            $options = [];
            foreach ($bundleProduct->getBundleOptionsData() as $key => $optionData) {
                if (!(bool)$optionData['delete']) {
                    if (!(bool)$optionData['delete']) {
                        $option = $this->optionFactory->create(['data' => $optionData]);
                        $option->setSku($bundleProduct->getSku());
                        $option->setOptionId(null);
                        $links = [];
                        $bundleLinks = $bundleProduct->getBundleSelectionsData();
                        if (!empty($bundleLinks[$key])) {
                            foreach ($bundleLinks[$key] as $linkData) {
                                if (!(bool)$linkData['delete']) {
                                    /** @var \Magento\Bundle\Api\Data\LinkInterface $link */
                                    $link = $this->linkFactory->create(['data' => $linkData]);
                                    $linkProduct = $this->productRepository->getById($linkData['product_id']);
                                    $link->setSku($linkProduct->getSku());
                                    $link->setQty($linkData['selection_qty']);
                                    if (isset($linkData['selection_can_change_qty'])) {
                                        $link->setCanChangeQuantity($linkData['selection_can_change_qty']);
                                    }
                                    $links[] = $link;
                                }
                            }
                            $option->setProductLinks($links);
                            $options[] = $option;
                        }
                    }
                }
                $extension = $bundleProduct->getExtensionAttributes();
                $extension->setBundleProductOptions($options);
                $bundleProduct->setExtensionAttributes($extension);
            }
            $bundleProduct->getResource()->save();
        }
    }
    public function createGroupProduct(){
        /** @var \Magento\Catalog\Model\Product $groupProduct */
        $groupProduct = $this->productFactory->create();
        //group product data
        $data= [
            'sku'                    =>  'simplegroup3',
            'name'                   =>  'group product3',
            'attribute_set_id'       =>  4, //default attribute set
            'status'                 =>  \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED, //'1' -> ENABLE '2' -> DISABLE
            'visibiity'              =>  4,
            'type_id'                =>  'grouped',
            'price'                  =>  122.25,
            'stock_data'             =>  [

                'use_config_manage_stock'    =>  0,
                'manage_stock'               =>  1,
                'is_in_stock'                =>  1,
                'qty'                        =>  999,

            ]
        ];
        $groupProduct->setData($data);
        $groupProduct->save();
        //child simple product
        $childrenIds = array(1,2,3);
        $associated = array();
        $position = 0;
        $groupProduct= $this->productRepository->getById(4); //get product group
        foreach($childrenIds as $productId){
            $position++;
            //You need to load each product to get what you need in order to build $productLink
            $linkedProduct = $this->productRepository->getById($productId);
            $productLink = $this->productLinkFactory->create();
            $productLink->setSku($groupProduct->getSku()) //sku of product group
            ->setLinkType('associated')
                ->setLinkedProductSku($linkedProduct->getSku())
                ->setLinkedProductType($linkedProduct->getTypeId())
                ->setPosition($position)
                ->getExtensionAttributes()
                ->setQty(1);
            $associated[] = $productLink;
        }
        $groupProduct->setProductLinks($associated);
        $this->productRepository->save($groupProduct);
    }
    public function createAttributeSet($categorySetup,$eavSetup){

        $attributeSet = $this->attributeSetFactory->create();
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        $data = [
            'attribute_set_name' => 'Shabimaya',
            'entity_type_id' => $entityTypeId,
            'sort_order' => 200,
        ];
        $attributeSet->setData($data);
        $attributeSet->validate();
        $attributeSet->save();
        $attributeSet->initFromSkeleton($attributeSetId);
        $attributeSet->save();

        // Create custom attribute
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY,
            'shabihaha1',
            [
                'type' => 'varchar',
                'label' => 'Memory1',
                'backend' => '',
                'input' => 'text',
                'wysiwyg_enabled'   => false,
                'source' => '',
                'required' => false,
                'sort_order' => 5,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true,
                'attribute_set_id' => 'Shabimaya',
            ]
            );

         $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY,
             'shabihehe1',
             [
                 'type' => 'varchar',
                 'label' => 'shabi21',
                 'backend' => '',
                 'input' => 'text',
                 'wysiwyg_enabled'   => false,
                 'source' => '',
                 'required' => false,
                 'sort_order' => 5,
                 'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                 'used_in_product_listing' => true,
                 'visible_on_front' => true,
                 'attribute_set_id' => 'Shabimaya',
             ]
         );

          $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY,
              'shabiheihei1',
              [
                  'type' => 'varchar',
                  'label' => 'cool1',
                  'backend' => '',
                  'input' => 'text',
                  'wysiwyg_enabled'   => false,
                  'source' => '',
                  'required' => false,
                  'sort_order' => 5,
                  'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
                  'used_in_product_listing' => true,
                  'visible_on_front' => true,
                  'attribute_set_id' => 'Shabimaya',
              ]
          );


    }
    public function createCategory(){
        // get the current stores root category
        $parentId = $this->storeManager->getStore()->getRootCategoryId();
        $parentCategory = $this->categoryFactory->create()->load($parentId);
        $rootPath = $parentCategory->getPath();
        /** @var \Magento\Catalog\Model\Category  $rootCategory*/
        $rootCategory = $this->categoryFactory->create();
        $data = [
            'name' => 'custom2',
            'path' => $rootPath, //root path id = 1
            'is_active' => 1,
            'url_key' => 'Test Category2',
            'display_mode' => 'PRODUCTS'

        ];
        $rootCategory->setData($data);
        $rootCategory->save();
        // assign custom product into root cateogry
//        $rootCategoryIds = $rootCategory->getId();

//        $simpleproduct = $this->productRepository->get('testproduct2');
//        $this->categoryLinkManagement->assignProductToCategories($simpleproduct->getSku(),[Category::TREE_ROOT_ID,$rootCategoryIds]);
//
//        $configproduct = $this->productRepository->get('test-configurable1');
//        $this->categoryLinkManagement->assignProductToCategories($configproduct->getSku(),[Category::TREE_ROOT_ID,$rootCategoryIds]);
//
//        $bundleproduct = $this->productRepository->get('Bundle product2');
//        $this->categoryLinkManagement->assignProductToCategories($bundleproduct->getSku(),[Category::TREE_ROOT_ID,$rootCategoryIds]);
//
//        $groupproduct = $this->productRepository->get('simplegroup3');
//        $this->categoryLinkManagement->assignProductToCategories($groupproduct->getSku(),[Category::TREE_ROOT_ID,$rootCategoryIds]);
    }

}