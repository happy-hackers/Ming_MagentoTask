<?php
namespace Zou\Demo\Block;

class PhysicalStores extends \Magento\Framework\View\Element\Template
{
    protected $_physicalStoresHelper;
    protected $_physicalStore;
    protected $scopeConfig;
    protected $_storeManager;
    protected $_countryCollection;
    protected $collection;
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context  $context,
        \Zou\Demo\Helper\Data $physicalStoresHelper,
        \Zou\Demo\Model\PhysicalStore $physicalStore,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        array $data = []
        ) {
            $this->_physicalStoresHelper = $physicalStoresHelper;
            $this->_physicalStore = $physicalStore;
            $this->scopeConfig = $context->getScopeConfig();
            $this->_storeManager = $context->getStoreManager(); 
            $this->_countryCollection =$countryCollection;
            parent::__construct($context, $data);
    }
    
    public function getPhysicalStores()
    {
        $collection = $this->_physicalStore->getCollection();
        $collection->addFieldToFilter('status',1);
        $collection->setOrder('name','asc');
        $physicalStores = [];
        foreach ($collection as $_store){
            $physicalStores[] = $_store;
        }
        usort($physicalStores, array($this, '_sortByName'));
      
        return $physicalStores;
    }
    
    public function _sortByName($a, $b)
    {
        $x = trim($a->getName());
        $y = trim($b->getName());
        if ($x == '') return 1;
        if ($y == '') return -1;
        return strnatcmp($x, $y);
    }
    
    public function getMediaUrl(){
        $mediaUrl = $this->_context->getStoreManager()->getStore()->getBaseUrl('media');
        return $mediaUrl;
    }
    
    public function getConfig($key, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();
    
        $result = $this->scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }
    
    public function getCountry($foregroundCountries='')
    {
        $collection=$this->_countryCollection->loadData()->setForegroundCountries(
            $foregroundCountries
            )->toOptionArray(
                false
                );
        return $collection;
    }
    
    public function isEnabledStaffs()
    {
        return $this->getConfig('demo/physicalstores/enabled');
    }
}
