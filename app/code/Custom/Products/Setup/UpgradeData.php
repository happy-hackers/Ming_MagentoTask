<?php
namespace Custom\Products\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Bundle\Api\Data\OptionInterfaceFactory;
use Magento\Bundle\Api\Data\LinkInterfaceFactory;
use \Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

use Custom\Products\Helper\AreaCode;

class UpgradeData implements  UpgradeDataInterface{

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var OptionInterfaceFactory
     */
    protected $optionFactory;

    /**
     * @var LinkInterfaceFactory
     */
    protected $linkFactory;

    /**
     * UpgradeData constructor.
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param OptionInterfaceFactory $optionFactory
     * @param LinkInterfaceFactory $linkFactory
     * @param ProductRepositoryInterface $productRepository
     * @param AreaCode $areaCode
     */
    public function __construct(
        ProductFactory  $productFactory,
        OptionInterfaceFactory $optionFactory,
        LinkInterfaceFactory   $linkFactory,
        ProductRepositoryInterface $productRepository,
        AreaCode $areaCode
    )
    {
        $areaCode->execute();
        $this->optionFactory = $optionFactory;
        $this->linkFactory = $linkFactory;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;


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
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
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
            $bundleProduct->save();
        }
    }
}