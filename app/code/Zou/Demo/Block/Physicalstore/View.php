<?php
namespace Zou\Demo\Block\Physicalstore;

use Zou\Demo\Block\PhysicalStores;

class View extends \Zou\Demo\Block\PhysicalStores
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_physicalStoresHelper;

    /**
     * @var \Ves\Blog\Model\PhysicalStore
     */
    protected $_physicalStore;
    protected $_collection;
    protected $_physicalstoresBlock;
    protected $_physicalStoreStaff;
    protected $_countryCollection;
    /**
     * @param \Magento\Framework\View\Element\Template\Context
     * @param \Magento\Framework\Registry
     * @param \Ves\Blog\Model\PhysicalStore
     * @param \Ves\Blog\Model\Category
     * @param \Ves\Blog\Helper\Data
     * @param array
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Zou\Demo\Model\PhysicalStore $physicalStore,
        \Zou\Demo\Helper\Data $physicalStoresHelper,
        \Zou\Demo\Model\PhysicalStoreStaff $physicalStoreStaff,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        array $data = []
        ) {
        $this->_physicalStoresHelper       = $physicalStoresHelper;
        $this->_coreRegistry     = $registry;
        $this->_physicalStore = $physicalStore;
        $this->_physicalStoreStaff=$physicalStoreStaff;
        $this->_countryCollection=$countryCollection;
        parent::__construct($context, $physicalStoresHelper, $physicalStore,$countryCollection);
    }

    public function getConfig($key, $default = '')
    {
        if($this->hasData($key)){
            return $this->getData($key);
        }
        $result = $this->_physicalStoresHelper->getConfig($key);
        $c = explode("/", $key);
        if($this->hasData($c[1])){
            return $this->getData($c[1]);
        }
        if($result == ""){
            $this->setData($c[1], $default);
            return $default;
        }
        $this->setData($c[1], $result);
        return $result;
    }

    public function _toHtml(){
        $physicalstore = $this->getPhysicalStore();
        if(!$physicalstore || !$physicalstore->getId()){
            return parent::_toHtml();
        }
        return parent::_toHtml();
    }

    /**
     * Prepare breadcrumbs
     *
     * @param \Magento\Cms\Model\Page $brand
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $physicalstore = $this->getPhysicalStore();
        if($physicalstore && $physicalstore->getId()){
            $page_title = $physicalstore->getName();
            $show_breadcrumbs = true;
            if($show_breadcrumbs && $breadcrumbsBlock){
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Go to Home Page'),
                        'link'  => $baseUrl
                    ]
                    );
            
                $breadcrumbsBlock->addCrumb(
                    'butiker',
                    [
                        'label' => __('Physical Stores'),
                        'title' => __('Physical Stores'),
                        'link'  => $this->getUrl('demo/physicalstore')
                    ]
                    );
            
                $breadcrumbsBlock->addCrumb(
                    'zouphysicalstores',
                    [
                        'label' => $page_title,
                        'title' => $page_title,
                        'link'  => ''
                    ]
                    );
            }
        }
        
    }

    /**
     * Set brand collection
     * @param \Ves\Blog\Model\PhysicalStore
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getCollection(){
        return $this->_collection;
    }

    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $physicalstore = $this->getPhysicalStore();
        if($physicalstore && $physicalstore->getId()){
            $page_title = $physicalstore->getName();
            $meta_description = $physicalstore->getTitle();
            $meta_keywords = $physicalstore->getTitle();
            $this->_addBreadcrumbs();
            $this->pageConfig->addBodyClass('zouphysicalstores-page');
            $this->pageConfig->addBodyClass('physicalstores-physicalstore-' . $physicalstore->getName());
            if($page_title){
                $this->pageConfig->getTitle()->set($page_title);
            }
            if($meta_keywords){
                $this->pageConfig->setKeywords($meta_keywords);
            }
            if($meta_description){
                $this->pageConfig->setDescription($meta_description);
            }
        }
        
        return parent::_prepareLayout();
    }

    public function getPhysicalStore(){
        return $this->_coreRegistry->registry('current_physicalstore');
    }

    public function getPhysicalStoreStaff(){
        $pyStore = $this->getPhysicalStore();
        $pyStoreStaff = $this->_physicalStoreStaff->getCollection()
        ->addFieldToFilter('status',1)
        ->addFieldToFilter("store_id", $pyStore->getId());
        $pyStoreStaff->setOrder('sort_order','asc');
        return $pyStoreStaff;
    }

    public function getOneCountry($foregroundCountries=''){
        $options = $this->_countryCollection->loadData()->setForegroundCountries(
                $foregroundCountries
                )->toOptionArray(
                    false
                    );
        return $options;
    }

    public function getImagePath(){
        return $this->_physicalStoresHelper->getBaseUrlMedia();
    }
}