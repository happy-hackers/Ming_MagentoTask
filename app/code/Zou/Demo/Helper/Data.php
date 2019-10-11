<?php
//当前插件的帮助类，通用函数。
namespace Zou\Demo\Helper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Customer\Model\Context;
class Data extends \Magento\Framework\App\Helper\AbstractHelper{
    protected $_storeManager;
    protected $_responseFactory;
    protected $_url;
    protected $_objectManager;
    protected $httpContext;
    protected $_coreRegistry;
    protected $_product = null;
    public function __construct(
        \Magento\Framework\App\Helper\Context  $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\Registry $registry
        ) {
            
            parent::__construct($context);
            $this->_responseFactory = $responseFactory;
            $this->_url = $context->getUrlBuilder();
            $this->scopeConfig = $context->getScopeConfig();
            $this->_objectManager = $objectManager;
            $this->httpContext = $httpContext;
            $this->_storeManager = $storeManager;
            $this->_coreRegistry = $registry;
    }

    public function getBaseUrlMedia($path = '', $secure = false)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, $secure) . $path;
    }
    
    public function show404(){
        $url = $this->_url->getUrl('noroute');
        $this->_responseFactory->create()->setRedirect($url)->sendResponse();
        exit();
    }
    
    
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }
    
    /**
     * Get store config
     *
     * @param string $config
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue($config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getCurrentProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function getBestSellerProducts(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productBlock = $objectManager->get('\Magento\Catalog\Block\Product\AbstractProduct');
        $productCollection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $reportCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory'); 
        $items = $reportCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection'); 
        $items->setPeriod('month');
        //$items->setPeriod('year');
        //$items->setPeriod('day');
        $productIds = [];
        foreach ($items as $item) {
            array_push($productIds, $item->getProductId());
        }
        $image = 'new_products_content_widget_grid';
        $productIds = array_unique($productIds);
        $productCollection = $productCollection->addAttributeToSelect('*')->addAttributeToFilter('entity_id',$productIds);
        return $productCollection;
    }


}